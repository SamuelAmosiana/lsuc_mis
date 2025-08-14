<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    public $timestamps = false;
    protected $table = 'department';
    protected $primaryKey = 'department_id';
    protected $fillable = ['name'];

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'department_id', 'department_id');
    }
}


