<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * Get all tags with post counts
     */
    public function index()
    {
        $tags = Tag::where('visibility', 'public')
            ->withCount(['posts' => function ($query) {
                $query->published();
            }])
            ->orderBy('name')
            ->get();

        return response()->json($tags);
    }

    /**
     * Get tag by slug with posts
     */
    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)
            ->where('visibility', 'public')
            ->firstOrFail();

        $posts = $tag->posts()
            ->published()
            ->with(['tags', 'authors'])
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json([
            'tag' => $tag,
            'posts' => $posts
        ]);
    }
}
