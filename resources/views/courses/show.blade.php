@extends('layouts.app')

@section('title', $course->meta_title ?: $course->title)
@section('meta_description', $course->meta_description ?: $course->excerpt)

@section('content')
<div class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Header -->
                <header class="mb-8">
                    <div class="flex items-center gap-x-4 text-xs mb-4">
                        <span class="text-gray-500">{{ $course->lessons->count() }} lessons</span>
                        @foreach($course->tags as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}" 
                               class="relative z-10 rounded-full bg-indigo-50 px-3 py-1.5 font-medium text-indigo-600 hover:bg-indigo-100">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl mb-4">
                        {{ $course->title }}
                    </h1>
                    @if($course->custom_excerpt)
                        <p class="text-xl text-gray-600 mb-6">{{ $course->custom_excerpt }}</p>
                    @endif
                    <div class="flex items-center gap-x-4">
                        @foreach($course->authors as $author)
                            <img src="{{ $author->profile_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) }}" 
                                 alt="{{ $author->name }}" class="h-12 w-12 rounded-full bg-gray-50">
                            <div>
                                <p class="font-semibold text-gray-900">
                                    <a href="{{ route('author.show', $author->slug) }}" class="hover:text-gray-600">
                                        {{ $author->name }}
                                    </a>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </header>

                <!-- Feature Image -->
                @if($course->feature_image)
                    <figure class="mb-8">
                        <img src="{{ $course->feature_image }}" alt="{{ $course->feature_image_alt }}" 
                             class="w-full rounded-lg">
                    </figure>
                @endif

                <!-- Content -->
                <div class="prose prose-lg max-w-none mb-12">
                    {!! $course->html !!}
                </div>
            </div>

            <!-- Sidebar - Lessons -->
            <div class="lg:col-span-1">
                <div class="sticky top-8">
                    <div class="rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Content</h3>
                        @if($course->lessons->count() > 0)
                            <ul class="space-y-3">
                                @foreach($course->lessons as $index => $lesson)
                                    <li>
                                        <a href="{{ route('lessons.show', $lesson->slug) }}" 
                                           class="flex items-start text-sm hover:text-indigo-600">
                                            <span class="flex-shrink-0 w-6 text-gray-400">{{ $index + 1 }}.</span>
                                            <span class="flex-1">{{ $lesson->title }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500">No lessons available yet.</p>
                        @endif
                        
                        <button class="mt-6 w-full rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Start Learning
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
