<div class="course-card">
    <a href="{{ url($post->url ?? '#') }}">
        @if($post->feature_image)
            <img src="{{ $post->feature_image }}" alt="{{ $post->feature_image_alt ?? $post->title }}" 
                 class="w-full h-48 object-cover">
        @endif
        
        <div class="p-6">
            <div class="flex items-center gap-2 mb-3">
                @foreach(($post->tags ?? collect())->take(2) as $tag)
                    <span class="text-xs px-2 py-1 bg-primary-100 dark:bg-primary-900/30 text-primary-700 dark:text-primary-400 rounded">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
            
            <h3 class="text-xl font-bold mb-2 hover:text-primary-600">
                {{ $post->title }}
            </h3>
            
            <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                {{ $post->excerpt ?? $post->custom_excerpt }}
            </p>
            
            <div class="flex items-center justify-between text-sm text-gray-500">
                <span>{{ $post->published_at?->format('M d, Y') }}</span>
                @if($post->post_type === 'course' && isset($post->lessons_count))
                    <span>{{ $post->lessons_count }} lessons</span>
                @endif
            </div>
        </div>
    </a>
</div>
