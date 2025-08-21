<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\FinancialAccount;
use App\Models\FinancialCategory;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Bill;
use App\Models\StudentFee;
use App\Models\Payment;
use App\Models\LedgerEntry;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AccountsController extends Controller
{
    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'accounts.layout';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Set the layout for all methods
        $this->middleware(function ($request, $next) {
            view()->share('accountsScripts', true);
            return $next($request);
        });
    }
    /**
     * Display the accounts dashboard.
     */
    public function dashboard()
    {
        $totalIncome = Income::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $totalReceivable = StudentFee::sum('balance');
        $cashBalance = FinancialAccount::where('category', 'cash')->first()->current_balance ?? 0;
        
        $recentTransactions = LedgerEntry::with('transaction')
            ->orderBy('entry_date', 'desc')
            ->take(10)
            ->get();
            
        $unpaidBills = Bill::with('student')
            ->where('status', '!=', 'paid')
            ->orderBy('due_date')
            ->take(5)
            ->get();
            
        return view('accounts.dashboard.index', compact(
            'totalIncome',
            'totalExpenses',
            'totalReceivable',
            'cashBalance',
            'recentTransactions',
            'unpaidBills'
        ));
    }
    
    /**
     * Display income records.
     */
    public function income(Request $request)
    {
        $query = Income::with('category')
            ->when($request->filled('category'), function($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->when($request->filled('date_from'), function($q) use ($request) {
                $q->whereDate('date_received', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                $q->whereDate('date_received', '<=', $request->date_to);
            });
            
        $incomes = $query->orderBy('date_received', 'desc')->paginate(20);
        $categories = FinancialCategory::where('type', 'income')->get();
        
        return view('accounts.income.index', compact('incomes', 'categories'));
    }
    
    /**
     * Store a new income record.
     */
    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:financial_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'source' => 'required|string|max:255',
            'date_received' => 'required|date',
            'payment_method' => 'required|string|in:cash,bank_transfer,check,mobile_money,other',
            'reference_number' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $income = new Income($validated);
            $income->received_by = Auth::id();
            $income->save();
            
            // Update account balance
            $account = FinancialAccount::where('category', 'cash')->first();
            if ($account) {
                $account->current_balance += $income->amount;
                $account->save();
                
                // Record ledger entry
                $income->ledgerEntries()->create([
                    'account_id' => $account->id,
                    'entry_date' => $income->date_received,
                    'debit' => $income->amount,
                    'credit' => 0,
                    'balance' => $account->current_balance,
                    'description' => 'Income received: ' . $income->source,
                    'reference_number' => $income->reference_number,
                    'created_by' => Auth::id(),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('accounts.income.index')
                ->with('success', 'Income recorded successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record income: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display expense records.
     */
    /**
     * Display expense records.
     */
    public function expenses(Request $request)
    {
        $query = Expense::with('category', 'paidBy')
            ->when($request->filled('category'), function($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            ->when($request->filled('date_from'), function($q) use ($request) {
                $q->whereDate('date_paid', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($q) use ($request) {
                $q->whereDate('date_paid', '<=', $request->date_to);
            });
            
        $expenses = $query->orderBy('date_paid', 'desc')->paginate(20);
        $categories = FinancialCategory::where('type', 'expense')->get();
        
        return view('accounts.expenses.index', compact('expenses', 'categories'));
    }
    
    /**
     * Store a new expense record.
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:financial_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'payee' => 'required|string|max:255',
            'date_paid' => 'required|date',
            'payment_method' => 'required|string|in:cash,bank_transfer,check,mobile_money,other',
            'reference_number' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $expense = new Expense($validated);
            $expense->paid_by = Auth::id();
            $expense->save();
            
            // Update account balance
            $account = FinancialAccount::where('category', 'cash')->first();
            if ($account) {
                $account->current_balance -= $expense->amount;
                $account->save();
                
                // Record ledger entry
                $expense->ledgerEntries()->create([
                    'account_id' => $account->id,
                    'entry_date' => $expense->date_paid,
                    'debit' => 0,
                    'credit' => $expense->amount,
                    'balance' => $account->current_balance,
                    'description' => 'Expense paid to ' . $expense->payee,
                    'reference_number' => $expense->reference_number,
                    'created_by' => Auth::id(),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('accounts.expenses.index')
                ->with('success', 'Expense recorded successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record expense: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Display financial reports.
     */
    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        // Get income by category
        $incomeByCategory = FinancialCategory::withSum(['incomes' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('date_received', [$startDate, $endDate]);
        }], 'amount')
        ->where('type', 'income')
        ->get();
        
        // Get expenses by category
        $expenseByCategory = FinancialCategory::withSum(['expenses' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('date_paid', [$startDate, $endDate]);
        }], 'amount')
        ->where('type', 'expense')
        ->get();
        
        // Generate cash flow data
        $cashFlow = [];
        $currentDate = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);
        
        while ($currentDate->lte($endDateObj)) {
            $date = $currentDate->format('Y-m-d');
            
            $income = Income::whereDate('date_received', $date)->sum('amount');
            $expense = Expense::whereDate('date_paid', $date)->sum('amount');
            
            $cashFlow[] = [
                'date' => $date,
                'income' => $income,
                'expense' => $expense,
                'net' => $income - $expense
            ];
            
            $currentDate->addDay();
        }
        
        return view('accounts.reports.index', compact(
            'incomeByCategory',
            'expenseByCategory',
            'cashFlow',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Display student fees management.
     */
    public function studentFees(Request $request)
    {
        $query = StudentFee::with(['bill.student', 'feeType'])
            ->when($request->filled('status'), function($q) use ($request) {
                if ($request->status === 'unpaid') {
                    $q->whereRaw('(amount - COALESCE(amount_paid, 0) - COALESCE(discount, 0)) = amount');
                } elseif ($request->status === 'partial') {
                    $q->whereRaw('(amount - COALESCE(amount_paid, 0) - COALESCE(discount, 0)) > 0')
                      ->where('amount_paid', '>', 0);
                } elseif ($request->status === 'paid') {
                    $q->whereRaw('(amount - COALESCE(amount_paid, 0) - COALESCE(discount, 0)) <= 0');
                } elseif ($request->status === 'overdue') {
                    $q->where('due_date', '<', now())
                      ->whereRaw('(amount - COALESCE(amount_paid, 0) - COALESCE(discount, 0)) > 0');
                }
            })
            ->when($request->filled('student_id'), function($q) use ($request) {
                $q->whereHas('bill', function($query) use ($request) {
                    $query->where('student_id', $request->student_id);
                });
            })
            ->when($request->filled('due_date'), function($q) use ($request) {
                $today = now()->format('Y-m-d');
                if ($request->due_date === 'today') {
                    $q->whereDate('due_date', $today);
                } elseif ($request->due_date === 'week') {
                    $q->whereBetween('due_date', [$today, now()->addWeek()->format('Y-m-d')]);
                } elseif ($request->due_date === 'month') {
                    $q->whereBetween('due_date', [$today, now()->addMonth()->format('Y-m-d')]);
                } elseif ($request->due_date === 'overdue') {
                    $q->where('due_date', '<', $today);
                }
            });
            
        $fees = $query->orderBy('due_date')->paginate(20);
        $students = Student::select('id', 'name', 'student_id')->get();
        
        return view('accounts.fees.index', compact('fees', 'students'));
    }
    
    /**
     * Record a payment for a student fee.
     */
    public function recordPayment(Request $request, $feeId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|in:cash,bank_transfer,check,mobile_money,other',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $fee = StudentFee::findOrFail($feeId);
            $balance = $fee->amount - $fee->amount_paid - $fee->discount;
            
            if ($validated['amount'] > $balance) {
                return back()->with('error', 'Payment amount cannot exceed the outstanding balance.');
            }
            
            // Record payment
            $payment = new Payment([
                'student_fee_id' => $fee->id,
                'amount' => $validated['amount'],
                'payment_date' => $validated['payment_date'],
                'payment_method' => $validated['payment_method'],
                'reference_number' => $validated['reference_number'],
                'notes' => $validated['notes'],
                'recorded_by' => Auth::id(),
            ]);
            $payment->save();
            
            // Update fee amount paid
            $fee->amount_paid += $validated['amount'];
            
            // Update fee status if fully paid
            $newBalance = $fee->amount - $fee->amount_paid - $fee->discount;
            if ($newBalance <= 0) {
                $fee->status = 'paid';
                $fee->paid_at = now();
            } else {
                $fee->status = $fee->amount_paid > 0 ? 'partial' : 'unpaid';
            }
            $fee->save();
            
            // Update account balance
            $account = FinancialAccount::where('category', 'cash')->first();
            if ($account) {
                $account->current_balance += $validated['amount'];
                $account->save();
                
                // Record ledger entry
                $fee->ledgerEntries()->create([
                    'account_id' => $account->id,
                    'entry_date' => $validated['payment_date'],
                    'debit' => $validated['amount'],
                    'credit' => 0,
                    'balance' => $account->current_balance,
                    'description' => 'Payment received for ' . ($fee->feeType->name ?? 'fee') . ' - ' . ($fee->bill->student->name ?? 'Student'),
                    'reference_number' => $validated['reference_number'],
                    'created_by' => Auth::id(),
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('accounts.fees.index')
                ->with('success', 'Payment recorded successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record payment: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Get payment history for a fee.
     */
    public function getPaymentHistory($feeId)
    {
        $fee = StudentFee::with(['payments.recordedBy', 'bill.student'])->findOrFail($feeId);
        return response()->json([
            'success' => true,
            'html' => view('accounts.fees.partials.payment_history', ['fee' => $fee])->render()
        ]);
    }
    
    /**
     * Display financial reports.
     */
    public function reports(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        
        // Get income by category
        $incomeByCategory = FinancialCategory::withSum(['incomes' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('date_received', [$startDate, $endDate]);
        }], 'amount')
        ->where('type', 'income')
        ->get();
        
        $expenseByCategory = FinancialCategory::withSum(['expenses' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('date_paid', [$startDate, $endDate]);
        }], 'amount')
        ->where('type', 'expense')
        ->get();
        
        $cashFlow = [];
        $currentDate = Carbon::parse($startDate);
        $endDateObj = Carbon::parse($endDate);
        
        while ($currentDate->lte($endDateObj)) {
            $date = $currentDate->format('Y-m-d');
            
            $income = Income::whereDate('date_received', $date)->sum('amount');
            $expense = Expense::whereDate('date_paid', $date)->sum('amount');
            
            $cashFlow[] = [
                'date' => $date,
                'income' => $income,
                'expense' => $expense,
                'net' => $income - $expense
            ];
            
            $currentDate->addDay();
        }
        
        return view('accounts.reports.index', compact(
            'incomeByCategory',
            'expenseByCategory',
            'cashFlow',
            'startDate',
            'endDate'
        ));
    }
    
    /**
     * Display student fee management.
     */
    public function studentFees(Request $request)
    {
        $query = StudentFee::with(['bill.student', 'feeType'])
            ->when($request->filled('status'), function($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('student_id'), function($q) use ($request) {
                $q->whereHas('bill', function($q) use ($request) {
                    $q->where('student_id', $request->student_id);
                });
            });
            
        $fees = $query->orderBy('due_date')->paginate(20);
        $students = Student::all();
        
        return view('accounts.fees.index', compact('fees', 'students'));
    }
    
    /**
     * Record a payment for a student fee.
     */
    public function recordPayment(Request $request, StudentFee $fee)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $fee->balance,
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $payment = $fee->recordPayment(
                $request->amount,
                $request->payment_date,
                $request->payment_method,
                $request->reference_number,
                Auth::id(),
                $request->notes,
                Auth::id()
            );
            
            DB::commit();
            
            return redirect()->back()->with('success', 'Payment recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to record payment: ' . $e->getMessage());
        }
    }
}
