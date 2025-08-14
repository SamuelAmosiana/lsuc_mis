<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeLSC extends Model
{
    public $timestamps = false;
    protected $table = 'grade';
    protected $primaryKey = 'grade_id';
    protected $fillable = ['name'];
}


