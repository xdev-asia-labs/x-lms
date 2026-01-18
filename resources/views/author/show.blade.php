@extends('layouts.app')

@section('title', $author->meta_title ?: $author->name)
@section('meta_description', $author->meta_description ?: $author->bio)

@section('content')
<div class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Author Info -->
        <div class="mx-auto max-w-2xl text-center mb-12">
            <img src="{{ $author->profile_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) }}" 
                 alt="{{ $author->name }}" class="h-32 w-32 rounded-full mx-auto mb-4">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">{{ $author->name }}</h2>
            @if($author->bio)
                <p class="mt-2 text-lg leading-8 text-gray-600">{{ $author->bio }}</p>
            @endif
            @if($author->website)
                <p class="mt-2">
                    <a href="{{ $author->website }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                        {{ $author->website }}
                    </a>
                </p>
            @endif
        </div>

        <!-- Author's Posts -->
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
                        @foreach($post->tags->take(2) as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}" 
                               class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">
                                {{ $tag->name }}
                            </a>
                        @endforeach
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
                    <p class="text-gray-500">No posts found for this author.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
