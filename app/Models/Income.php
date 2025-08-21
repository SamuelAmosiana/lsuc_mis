<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Income extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'amount',
        'source',
        'reference_number',
        'description',
        'date_received',
        'payment_method',
        'received_by',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'date_received' => 'date',
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

    public function ledgerEntries(): MorphMany
    {
        return $this->morphMany(LedgerEntry::class, 'transaction');
    }

    protected static function booted()
    {
        static::created(function ($income) {
            // Create ledger entry when income is recorded
            $income->ledgerEntries()->create([
                'entry_date' => $income->date_received,
                'reference_number' => $income->reference_number,
                'transaction_type' => 'income',
                'description' => 'Income from ' . $income->source . ': ' . $income->description,
                'credit' => $income->amount,
                'balance' => FinancialAccount::where('category', 'cash')->first()->current_balance + $income->amount,
                'account_id' => FinancialAccount::where('category', 'cash')->first()->id,
                'created_by' => $income->created_by,
            ]);

            // Update account balance
            $account = FinancialAccount::where('category', 'cash')->first();
            $account->current_balance += $income->amount;
            $account->save();
        });
    }
}
