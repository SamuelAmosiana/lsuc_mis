<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    public $timestamps = false;
    protected $table = 'student';
    protected $primaryKey = 'student_id';
    protected $fillable = [
        'name','gender','phone','next_of_kin','nrc','address','school_id','programme_id'
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id', 'school_id');
    }

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(CourseLSC::class, 'student_course', 'student_id', 'course_id');
    }
}


