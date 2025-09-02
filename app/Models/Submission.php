<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submission';
    protected $fillable = ['student_id','course_id','title','notes','submitted_at'];
    protected $casts = [
        'submitted_at' => 'datetime',
    ];
}


