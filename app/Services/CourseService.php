<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use illuminate\Support\Collection;

class CourseService
{
    public function enrolUser(Course $course)
    {
        $user = Auth::user();

        if (!$course->courseStudents()->where('user_id', $user->id)->exists()) {
            $course->courseStudents()->create([
                'user_id' => $user->id,
                'is_active' => true,
            ]);
        }

        return $user->name;
    }

    public function getFristSectionAndContent(Course $course)
    {
        $fristSectionId = $course->courseSections()->orderBy('id')->value('id');
        $fristContentId = $fristSectionId
            ? $course->courseContents()->find($fristSectionId)->sectionContens()->orderBy('id')->value('id')
            : null;

        return [
            'fristSectionId' => $fristSectionId,
            'fristContentId' => $fristContentId,
        ];
    }

    public function getLearningData(Course $course, $contentSectionId, $sectionContentId)
    {
        $course->load(['courseSections.sectionContents']);

        $currentSection = $course->courseSections->find($contentSectionId);
        $currentContent = $currentSection ? $currentSection->sectionContents->find($sectionContentId) : null;

        $nextContent = null;

        if ($currentContent) {
            $nextContent = $currentSection->sectionContents
            ->where('id', '>', $sectionContentId)
            ->sortBy('id')
            ->first();
        }

        if (!$nextContent && $currentSection) {
            $nextSection = $course->courseSections
            ->where('id', '>', $currentSection->id)
            ->sortBy('id')
            ->first();

            if ($nextSection) {
                $nextContent = $nextSection->sectionContents->sortBy('id')->first();
            }
        }

        return [
            'course' => $course,
            'currentSection' => $currentSection,
            'currentContent' => $currentContent,
            'nextContent' => $nextContent,
            'isFinished' => !$nextContent,
        ];
    }
}
