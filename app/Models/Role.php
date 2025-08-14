<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    public $timestamps = false;
    protected $table = 'role';
    protected $primaryKey = 'role_id';
    protected $fillable = ['role_name','role_description'];

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'staff_role', 'role_id', 'staff_id');
    }
}


