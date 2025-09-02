<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Staff extends Model
{
    public $timestamps = false;
    protected $table = 'staff';
    protected $primaryKey = 'staff_id';
    protected $fillable = [
        'name','email','address','t_pin','phone','nrc','gender','next_of_kin','department_id'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'staff_role', 'staff_id', 'role_id');
    }
    
    /**
     * Get the courses that the staff member is assigned to.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Course::class, 'lecturer_course', 'staff_id', 'course_id');
    }
}


