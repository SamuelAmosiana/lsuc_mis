<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'amount',
        'payee',
        'reference_number',
        'description',
        'date_paid',
        'payment_method',
        'approved_by',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'date_paid' => 'date',
        'amount' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinancialCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function ledgerEntries(): MorphMany
    {
        return $this->morphMany(LedgerEntry::class, 'transaction');
    }

    protected static function booted()
    {
        static::created(function ($expense) {
            // Create ledger entry when expense is recorded
            $expense->ledgerEntries()->create([
                'entry_date' => $expense->date_paid,
                'reference_number' => $expense->reference_number,
                'transaction_type' => 'expense',
                'description' => 'Expense to ' . $expense->payee . ': ' . $expense->description,
                'debit' => $expense->amount,
                'balance' => FinancialAccount::where('category', 'cash')->first()->current_balance - $expense->amount,
                'account_id' => FinancialAccount::where('category', 'cash')->first()->id,
                'created_by' => $expense->created_by,
            ]);

            // Update account balance
            $account = FinancialAccount::where('category', 'cash')->first();
            $account->current_balance -= $expense->amount;
            $account->save();
        });
    }
}
