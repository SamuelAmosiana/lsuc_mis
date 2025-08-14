<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryLoan extends Model
{
    use HasFactory;

    protected $fillable = ['library_item_id','user_id','borrowed_at','due_at','returned_at'];

    protected $casts = [
        'borrowed_at' => 'date',
        'due_at' => 'date',
        'returned_at' => 'date',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(LibraryItem::class, 'library_item_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


