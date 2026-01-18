<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Get all posts with optional filters
     */
    public function index(Request $request)
    {
        $query = Post::with(['tags', 'authors'])
            ->published();

        // Filter by post type
        if ($request->has('type')) {
            $query->type($request->type);
        }

        // Filter by course_id for lessons
        if ($request->has('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        // Filter by featured
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Pagination
        $perPage = $request->input('limit', 15);
        $posts = $query->orderBy('published_at', 'desc')
            ->paginate($perPage);

        return response()->json($posts);
    }

    /**
     * Get single post by slug
     */
    public function show($slug)
    {
        $post = Post::with(['tags', 'authors', 'lessons'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        return response()->json($post);
    }

    /**
     * Get lessons for a specific course
     */
    public function lessons($courseId)
    {
        $lessons = Post::where('course_id', $courseId)
            ->where('post_type', 'lesson')
            ->published()
            ->orderBy('lesson_order')
            ->get();

        return response()->json($lessons);
    }

    /**
     * Search posts
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $posts = Post::where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('plaintext', 'like', "%{$query}%")
                  ->orWhere('custom_excerpt', 'like', "%{$query}%");
            })
            ->published()
            ->with(['tags', 'authors'])
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json($posts);
    }
}
