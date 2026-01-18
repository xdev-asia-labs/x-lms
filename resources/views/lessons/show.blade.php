@extends('layouts.app')

@section('title', $lesson->meta_title ?: $lesson->title)
@section('meta_description', $lesson->meta_description ?: $lesson->excerpt)

@section('content')
<div class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar - Course Navigation -->
            @if($lesson->course)
            <div class="lg:col-span-1 order-2 lg:order-1">
                <div class="sticky top-8">
                    <div class="rounded-lg border border-gray-200 p-4">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">
                            <a href="{{ route('courses.show', $lesson->course->slug) }}" class="hover:text-indigo-600">
                                {{ $lesson->course->title }}
                            </a>
                        </h4>
                        <div class="text-xs text-gray-500 mb-4">
                            Lesson {{ $lesson->lesson_order }} of {{ $lesson->course->lessons->count() }}
                        </div>
                        <ul class="space-y-2 max-h-96 overflow-y-auto">
                            @foreach($lesson->course->lessons as $item)
                                <li>
                                    <a href="{{ route('lessons.show', $item->slug) }}" 
                                       class="flex items-start text-xs {{ $item->id === $lesson->id ? 'text-indigo-600 font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                                        <span class="flex-shrink-0 w-5">{{ $item->lesson_order }}.</span>
                                        <span class="flex-1">{{ $item->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <!-- Main Content -->
            <div class="lg:col-span-3 order-1 lg:order-2">
                <!-- Header -->
                <header class="mb-8">
                    @if($lesson->course)
                        <nav class="text-sm mb-4">
                            <a href="{{ route('courses.show', $lesson->course->slug) }}" class="text-indigo-600 hover:text-indigo-800">
                                ← Back to {{ $lesson->course->title }}
                            </a>
                        </nav>
                    @endif
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl mb-4">
                        {{ $lesson->title }}
                    </h1>
                    <div class="flex items-center gap-x-4">
                        @foreach($lesson->authors as $author)
                            <img src="{{ $author->profile_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) }}" 
                                 alt="{{ $author->name }}" class="h-10 w-10 rounded-full bg-gray-50">
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

                <!-- Content -->
                <div class="prose prose-lg max-w-none">
                    {!! $lesson->html !!}
                </div>

                <!-- Navigation -->
                @if($lesson->course)
                    <div class="mt-12 pt-8 border-t border-gray-200 flex justify-between">
                        @php
                            $currentIndex = $lesson->course->lessons->search(fn($l) => $l->id === $lesson->id);
                            $prevLesson = $currentIndex > 0 ? $lesson->course->lessons[$currentIndex - 1] : null;
                            $nextLesson = $currentIndex < $lesson->course->lessons->count() - 1 ? $lesson->course->lessons[$currentIndex + 1] : null;
                        @endphp
                        
                        <div>
                            @if($prevLesson)
                                <a href="{{ route('lessons.show', $prevLesson->slug) }}" 
                                   class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    Previous: {{ $prevLesson->title }}
                                </a>
                            @endif
                        </div>
                        
                        <div>
                            @if($nextLesson)
                                <a href="{{ route('lessons.show', $nextLesson->slug) }}" 
                                   class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800">
                                    Next: {{ $nextLesson->title }}
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
