@extends('layouts.app')

@section('title', $tag->meta_title ?: $tag->name)
@section('meta_description', $tag->meta_description)

@section('content')
<div class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-12">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $tag->name }}</h2>
            @if($tag->description)
                <p class="mt-2 text-lg leading-8 text-gray-600">{{ $tag->description }}</p>
            @endif
        </div>

        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-12 lg:grid-cols-3">
            @forelse($posts as $post)
                <article class="flex flex-col items-start">
                    @if($post->feature_image)
                        <img src="{{ $post->feature_image }}" alt="{{ $post->feature_image_alt }}" 
                             class="w-full h-48 object-cover rounded-lg mb-4">
                    @endif
                    <div class="flex items-center gap-x-4 text-xs">
                        <time datetime="{{ $post->published_at }}" class="text-gray-500">
                            {{ $post->published_at->format('M d, Y') }}
                        </time>
                        <span class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600">
                            {{ ucfirst($post->post_type) }}
                        </span>
                    </div>
                    <div class="group relative">
                        <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                            <a href="{{ $post->url }}">
                                <span class="absolute inset-0"></span>
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">{{ $post->excerpt }}</p>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No posts found for this tag.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
