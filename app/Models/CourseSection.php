<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SectionContent;



class CourseSection extends Model
{
    //
    use SoftDeletes;

    protected $fillable = [
        'name', // Web Design category name
        'course_id', // slug = web-design
        'position', // slug = web-design
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function sectionContents(): HasMany
    {
        return $this->hasMany(SectionContent::class);
    }
}
