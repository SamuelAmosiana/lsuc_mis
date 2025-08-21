<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bill_number',
        'student_id',
        'academic_year',
        'term',
        'total_amount',
        'amount_paid',
        'due_date',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($bill) {
            $bill->bill_number = static::generateBillNumber();
            $bill->status = 'unpaid';
            $bill->amount_paid = 0;
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(StudentFee::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updateStatus()
    {
        $totalFees = $this->fees()->sum('amount');
        $totalPaid = $this->payments()->sum('amount');
        
        if ($totalPaid >= $totalFees) {
            $this->status = 'paid';
        } elseif ($totalPaid > 0) {
            $this->status = 'partially_paid';
        } else {
            $this->status = 'unpaid';
        }

        $this->amount_paid = $totalPaid;
        $this->save();
    }

    protected static function generateBillNumber(): string
    {
        $prefix = 'BILL-' . date('Y') . '-';
        $lastBill = static::where('bill_number', 'like', $prefix . '%')
            ->orderBy('bill_number', 'desc')
            ->first();

        if ($lastBill) {
            $lastNumber = (int) substr($lastBill->bill_number, strlen($prefix));
            $number = $lastNumber + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
