<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    //
    public function index()
    {
        $courseByCategory = Course::with('category')
        ->latest()
        ->get()
        ->groupBy(function ($course)) {
            return $course->category->name ?? 'Uncategorized';
        });
    }
}
