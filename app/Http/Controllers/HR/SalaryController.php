<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\User;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::with('user')
            ->latest()
            ->paginate(15);
            
        $staff = User::role('staff')->get();
        
        return view('hr.salaries.index', compact('salaries', 'staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'required|array',
            'allowances.*.name' => 'required|string',
            'allowances.*.amount' => 'required|numeric|min:0',
            'deductions' => 'required|array',
            'deductions.*.name' => 'required|string',
            'deductions.*.amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|in:bank_transfer,check,cash',
            'notes' => 'nullable|string|max:500',
        ]);

        $salary = Salary::create([
            'user_id' => $request->user_id,
            'basic_salary' => $request->basic_salary,
            'allowances' => json_encode($request->allowances),
            'deductions' => json_encode($request->deductions),
            'total_amount' => $this->calculateTotal($request->basic_salary, $request->allowances, $request->deductions),
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
            'processed_by' => auth()->id(),
        ]);

        return back()->with('success', 'Salary processed successfully');
    }

    private function calculateTotal($basic, $allowances, $deductions)
    {
        $totalAllowances = collect($allowances)->sum('amount');
        $totalDeductions = collect($deductions)->sum('amount');
        
        return $basic + $totalAllowances - $totalDeductions;
    }
}
