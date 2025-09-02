<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentMark extends Model
{
    protected $table = 'student_mark';
    protected $fillable = [
        'student_id','course_id','lecturer_staff_id','term','year','ca_score','exam_score','total'
    ];
    protected $casts = [
        'ca_score' => 'decimal:2',
        'exam_score' => 'decimal:2',
        'total' => 'decimal:2',
    ];
}


