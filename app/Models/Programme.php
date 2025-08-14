<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programme extends Model
{
    public $timestamps = false;
    protected $table = 'programme';
    protected $primaryKey = 'programme_id';
    protected $fillable = ['name','school_id'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'school_id');
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'programme_id', 'programme_id');
    }
}


