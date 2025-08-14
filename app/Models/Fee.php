<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    public $timestamps = false;
    protected $table = 'fee';
    protected $primaryKey = 'fee_id';
    protected $fillable = ['name','amount'];
}


