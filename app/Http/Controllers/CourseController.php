<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::courses()
            ->published()
            ->with(['tags', 'authors'])
            ->withCount('lessons');

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $courses = $query->orderBy('published_at', 'desc')->paginate(12);

        return view('courses.index', compact('courses'));
    }

    public function show($slug)
    {
        $course = Post::courses()
            ->where('slug', $slug)
            ->published()
            ->with(['tags', 'authors', 'lessons'])
            ->firstOrFail();

        return view('courses.show', compact('course'));
    }
}
