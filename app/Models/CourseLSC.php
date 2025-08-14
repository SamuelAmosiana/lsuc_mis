<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseLSC extends Model
{
    public $timestamps = false;
    protected $table = 'course';
    protected $primaryKey = 'course_id';
    protected $fillable = ['name','programme_id'];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(Programme::class, 'programme_id', 'programme_id');
    }
}


