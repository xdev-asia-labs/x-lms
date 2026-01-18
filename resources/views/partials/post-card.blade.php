<div class="card">
    <a href="{{ url($post->url ?? '#') }}">
        @if($post->feature_image)
            <img src="{{ $post->feature_image }}" alt="{{ $post->feature_image_alt ?? $post->title }}" 
                 class="w-full h-48 object-cover">
        @endif
        
        <div class="p-6">
            <div class="flex items-center gap-2 mb-3">
                @foreach(($post->tags ?? collect())->take(3) as $tag)
                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
            
            <h3 class="text-xl font-bold mb-2 hover:text-primary-600 transition">
                {{ $post->title }}
            </h3>
            
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                {{ $post->excerpt ?? $post->custom_excerpt }}
            </p>
            
            <div class="flex items-center gap-4 text-sm text-gray-500">
                @if($post->authors?->first())
                    <div class="flex items-center gap-2">
                        @if($post->authors->first()->profile_image)
                            <img src="{{ $post->authors->first()->profile_image }}" 
                                 alt="{{ $post->authors->first()->name }}"
                                 class="w-6 h-6 rounded-full">
                        @endif
                        <span>{{ $post->authors->first()->name }}</span>
                    </div>
                @endif
                <span>{{ $post->published_at?->format('M d, Y') }}</span>
            </div>
        </div>
    </a>
</div>
