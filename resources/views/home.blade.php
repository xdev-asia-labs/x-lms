@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-12">
    <!-- Hero Section -->
    <div class="text-center mb-16">
        <h1 class="text-5xl font-bold mb-4">Welcome to {{ config('app.name') }}</h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
            Learn, Build, and Grow with Our Comprehensive Learning Platform
        </p>
        <a href="{{ url('/courses') }}" class="btn-primary">
            Browse Courses
        </a>
    </div>

    <!-- Featured Courses -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold">Featured Courses</h2>
            <a href="{{ url('/courses') }}" class="text-primary-600 hover:text-primary-700">
                View all →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($featuredCourses ?? [] as $course)
                @include('partials.course-card', ['post' => $course])
            @endforeach
        </div>
    </section>

    <!-- Latest Articles -->
    <section class="mb-16">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold">Latest Articles</h2>
            <a href="{{ url('/blog') }}" class="text-primary-600 hover:text-primary-700">
                View all →
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestArticles ?? [] as $article)
                @include('partials.post-card', ['post' => $article])
            @endforeach
        </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="bg-primary-600 dark:bg-primary-700 rounded-lg p-12 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Stay Updated</h2>
        <p class="text-lg mb-6">Subscribe to our newsletter for the latest courses and articles.</p>
        <form action="{{ url('/api/newsletter/subscribe') }}" method="POST" class="max-w-md mx-auto flex">
            @csrf
            <input type="email" name="email" placeholder="Your email address" 
                   class="flex-1 px-4 py-3 rounded-l text-gray-900" required>
            <button type="submit" class="px-6 py-3 bg-white text-primary-600 font-semibold rounded-r hover:bg-gray-100">
                Subscribe
            </button>
        </form>
    </section>
</div>
@endsection
