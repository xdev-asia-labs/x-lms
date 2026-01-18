@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt)

@section('content')
<article class="bg-white py-12">
    <div class="mx-auto max-w-4xl px-6 lg:px-8">
        <!-- Header -->
        <header class="mb-8">
            <div class="flex items-center gap-x-4 text-xs mb-4">
                <time datetime="{{ $post->published_at }}" class="text-gray-500">
                    {{ $post->published_at->format('M d, Y') }}
                </time>
                @foreach($post->tags as $tag)
                    <a href="{{ route('tag.show', $tag->slug) }}" 
                       class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl mb-4">
                {{ $post->title }}
            </h1>
            @if($post->custom_excerpt)
                <p class="text-xl text-gray-600 mb-6">{{ $post->custom_excerpt }}</p>
            @endif
            <div class="flex items-center gap-x-4">
                @foreach($post->authors as $author)
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
        @if($post->feature_image)
            <figure class="mb-8">
                <img src="{{ $post->feature_image }}" alt="{{ $post->feature_image_alt }}" 
                     class="w-full rounded-lg">
                @if($post->feature_image_caption)
                    <figcaption class="mt-2 text-sm text-gray-600 text-center">
                        {{ $post->feature_image_caption }}
                    </figcaption>
                @endif
            </figure>
        @endif

        <!-- Content -->
        <div class="prose prose-lg max-w-none">
            {!! $post->html !!}
        </div>

        <!-- Tags -->
        @if($post->tags->count() > 0)
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <a href="{{ route('tag.show', $tag->slug) }}" 
                           class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800 hover:bg-gray-200">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection
