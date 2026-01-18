<?php

namespace App\Http\Controllers;

use App\Models\Post;

class LessonController extends Controller
{
    public function show($slug)
    {
        $lesson = Post::lessons()
            ->where('slug', $slug)
            ->published()
            ->with(['tags', 'authors', 'course.lessons'])
            ->firstOrFail();

        return view('lessons.show', compact('lesson'));
    }
}
