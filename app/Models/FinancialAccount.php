<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'category',
        'opening_balance',
        'current_balance',
        'currency',
        'description',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(LedgerEntry::class, 'account_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updateBalance(): void
    {
        $this->current_balance = $this->ledgerEntries()
            ->selectRaw('SUM(COALESCE(credit, 0) - COALESCE(debit, 0)) as balance')
            ->value('balance') + $this->opening_balance;
        
        $this->save();
    }

    public function getBalanceAsOf($date)
    {
        $balance = $this->opening_balance;
        
        $entries = $this->ledgerEntries()
            ->whereDate('entry_date', '<=', $date)
            ->orderBy('entry_date')
            ->orderBy('id')
            ->get();
            
        foreach ($entries as $entry) {
            $balance += ($entry->credit - $entry->debit);
        }
        
        return $balance;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
