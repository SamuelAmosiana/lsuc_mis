<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bill_id',
        'student_fee_id',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'received_by',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function studentFee(): BelongsTo
    {
        return $this->belongsTo(StudentFee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function receivedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function ledgerEntries(): MorphMany
    {
        return $this->morphMany(LedgerEntry::class, 'transaction');
    }

    protected static function booted()
    {
        static::created(function ($payment) {
            // Create ledger entry when payment is recorded
            $payment->ledgerEntries()->create([
                'entry_date' => $payment->payment_date,
                'reference_number' => $payment->reference_number,
                'transaction_type' => 'payment',
                'description' => 'Payment received for ' . ($payment->bill ? 'Bill #' . $payment->bill->bill_number : 'Fee Payment'),
                'credit' => $payment->amount,
                'balance' => FinancialAccount::where('category', 'cash')->first()->current_balance + $payment->amount,
                'account_id' => FinancialAccount::where('category', 'cash')->first()->id,
                'created_by' => $payment->created_by,
            ]);

            // Update account balance
            $account = FinancialAccount::where('category', 'cash')->first();
            $account->current_balance += $payment->amount;
            $account->save();
        });
    }
}
