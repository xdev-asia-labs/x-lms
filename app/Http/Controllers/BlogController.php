<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::blog()
            ->published()
            ->with(['tags', 'authors']);

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(12);

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::blog()
            ->where('slug', $slug)
            ->published()
            ->with(['tags', 'authors'])
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
