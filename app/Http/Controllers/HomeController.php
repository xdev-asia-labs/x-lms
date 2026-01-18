<?php

namespace App\Http\Controllers;

use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $featuredCourses = Post::courses()
            ->published()
            ->featured()
            ->with(['tags', 'authors'])
            ->withCount('lessons')
            ->take(3)
            ->get();

        $latestArticles = Post::blog()
            ->published()
            ->with(['tags', 'authors'])
            ->take(3)
            ->get();

        return view('home', compact('featuredCourses', 'latestArticles'));
    }
}
