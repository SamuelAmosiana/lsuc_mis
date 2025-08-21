<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentFee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bill_id',
        'fee_type_id',
        'amount',
        'discount',
        'amount_paid',
        'due_date',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($studentFee) {
            // Calculate balance
            $studentFee->balance = $studentFee->amount - $studentFee->amount_paid - $studentFee->discount;
            
            // Update status based on payment
            if ($studentFee->amount_paid >= $studentFee->amount) {
                $studentFee->status = 'paid';
            } elseif ($studentFee->amount_paid > 0) {
                $studentFee->status = 'partially_paid';
            } else {
                $studentFee->status = 'unpaid';
            }
        });

        static::saved(function ($studentFee) {
            // Update the parent bill's total amount
            $bill = $studentFee->bill;
            $bill->total_amount = $bill->fees()->sum('amount');
            $bill->save();
            $bill->updateStatus();
        });
    }

    public function bill(): BelongsTo
    {
        return $this->belongsTo(Bill::class);
    }

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FinancialCategory::class, 'fee_type_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function recordPayment($amount, $paymentDate, $paymentMethod, $reference, $receivedBy, $notes = null, $userId)
    {
        // Record the payment
        $payment = $this->payments()->create([
            'amount' => $amount,
            'payment_date' => $paymentDate,
            'payment_method' => $paymentMethod,
            'reference_number' => $reference,
            'received_by' => $receivedBy,
            'notes' => $notes,
            'created_by' => $userId
        ]);

        // Update the amount paid and status
        $this->amount_paid += $amount;
        $this->save();

        // Update the parent bill
        $this->bill->updateStatus();

        return $payment;
    }
}
