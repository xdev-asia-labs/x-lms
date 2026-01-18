@extends('layouts.app')

@section('title', 'Blog - ' . config('app.name'))

@section('content')
<div class="bg-white py-12">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-12">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Blog</h2>
            <p class="mt-2 text-lg leading-8 text-gray-600">
                Learn from our latest articles and tutorials
            </p>
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
                        @foreach($post->tags->take(2) as $tag)
                            <a href="{{ route('tag.show', $tag->slug) }}" 
                               class="relative z-10 rounded-full bg-gray-50 px-3 py-1.5 font-medium text-gray-600 hover:bg-gray-100">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                    <div class="group relative">
                        <h3 class="mt-3 text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                            <a href="{{ route('blog.show', $post->slug) }}">
                                <span class="absolute inset-0"></span>
                                {{ $post->title }}
                            </a>
                        </h3>
                        <p class="mt-5 line-clamp-3 text-sm leading-6 text-gray-600">{{ $post->excerpt }}</p>
                    </div>
                    <div class="relative mt-8 flex items-center gap-x-4">
                        @foreach($post->authors as $author)
                            <img src="{{ $author->profile_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($author->name) }}" 
                                 alt="{{ $author->name }}" class="h-10 w-10 rounded-full bg-gray-50">
                            <div class="text-sm leading-6">
                                <p class="font-semibold text-gray-900">
                                    <a href="{{ route('author.show', $author->slug) }}">
                                        <span class="absolute inset-0"></span>
                                        {{ $author->name }}
                                    </a>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">No posts found.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
