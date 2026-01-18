@extends('layouts.app')

@section('title', 'Courses - ' . config('app.name'))

@section('content')
<div class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-12">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Courses</h2>
            <p class="mt-2 text-lg leading-8 text-gray-600">
                Explore our comprehensive learning paths
            </p>
        </div>

        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-12 lg:grid-cols-3">
            @forelse($courses as $course)
                <article class="flex flex-col items-start">
                    @if($course->feature_image)
                        <img src="{{ $course->feature_image }}" alt="{{ $course->feature_image_alt }}" 
                             class="w-full h-48 object-cover rounded-lg mb-4">
                    @endif
                    <div class="flex items-center gap-x-4 text-xs">
                        <span class="text-gray-500">{{ $course->lessons_count }} lessons</span>
                        @foreach($course->tags->take(2) as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}" 
                               class="relative z-10 rounded-full bg-indigo-50 px-3 py-1.5 font-medium text-indigo-600 hover:bg-indigo-100">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                    <div class="group relative">
                        <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                            <a href="{{ route('courses.show', $course->slug) }}">
                                <span class="absolute inset-0"></span>
                                {{ $course->title }}
                            </a>
                        </h3>
                        <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">{{ $course->excerpt }}</p>
                    </div>
                    @if($course->featured)
                        <div class="mt-4">
                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800">
                                Featured
                            </span>
                        </div>
                    @endif
                </article>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No courses found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection
