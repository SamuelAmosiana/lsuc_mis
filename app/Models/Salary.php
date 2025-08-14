<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    public $timestamps = false;
    protected $table = 'salary';
    protected $primaryKey = 'salary_id';
    protected $fillable = ['name','amount','date_paid'];
    protected $casts = [
        'date_paid' => 'date',
    ];
}


