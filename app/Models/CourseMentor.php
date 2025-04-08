<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseMentor extends Model
{
    //
    use SoftDeletes;

 protected $fillable = [
    'user_id', // 1
    'course_id', // 1
    'about', // Lorem ipsum dolor sit amet
    'is_active', // 1
    ];

    public function Course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
