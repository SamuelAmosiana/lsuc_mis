<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    public $timestamps = false;
    protected $table = 'school';
    protected $primaryKey = 'school_id';
    protected $fillable = ['name'];

    public function programmes(): HasMany
    {
        return $this->hasMany(Programme::class, 'school_id', 'school_id');
    }
}


