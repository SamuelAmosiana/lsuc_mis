<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffProfile extends Model
{
    protected $table = 'staff_profiles';
    
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'nrc',
        'gender',
        'next_of_kin',
        'department_id',
        'position',
        'employment_date',
    ];
    
    protected $casts = [
        'employment_date' => 'date',
    ];
    
    /**
     * Get the user that owns the staff profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the staff record associated with the staff profile.
     */
    public function staff()
    {
        return $this->hasOne(Staff::class, 'email', 'email');
    }
    
    /**
     * Get the department that the staff belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
