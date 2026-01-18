<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid', 'title', 'slug', 'html', 'plaintext', 'mobiledoc', 'lexical',
        'feature_image', 'feature_image_alt', 'feature_image_caption',
        'featured', 'is_page', 'status', 'visibility', 'post_type',
        'course_id', 'lesson_order',
        'meta_title', 'meta_description', 'custom_excerpt',
        'og_image', 'og_title', 'og_description',
        'twitter_image', 'twitter_title', 'twitter_description',
        'published_at'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'is_page' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($post) {
            if (empty($post->uuid)) {
                $post->uuid = Str::uuid();
            }
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    // Relationships
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag')
            ->withPivot('sort_order')
            ->orderBy('sort_order');
    }

    public function authors()
    {
        return $this->belongsToMany(User::class, 'post_user')
            ->withPivot('sort_order')
            ->orderBy('sort_order');
    }

    public function course()
    {
        return $this->belongsTo(Post::class, 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(Post::class, 'course_id')
            ->where('post_type', 'lesson')
            ->orderBy('lesson_order');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'course_id');
    }

    public function enrolledMembers()
    {
        return $this->belongsToMany(Member::class, 'course_enrollments', 'course_id', 'member_id')
            ->withPivot('status', 'enrolled_at', 'completed_at', 'progress_percentage')
            ->withTimestamps();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class, 'lesson_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeType($query, $type)
    {
        return $query->where('post_type', $type);
    }

    public function scopeCourses($query)
    {
        return $query->where('post_type', 'course');
    }

    public function scopeLessons($query)
    {
        return $query->where('post_type', 'lesson');
    }

    public function scopeBlog($query)
    {
        return $query->where('post_type', 'blog');
    }

    public function scopeNews($query)
    {
        return $query->where('post_type', 'news');
    }

    public function scopeShowcase($query)
    {
        return $query->where('post_type', 'showcase');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    // Accessors
    public function getUrlAttribute()
    {
        $prefix = match($this->post_type) {
            'course' => '/courses',
            'lesson' => '/lessons',
            'blog' => '/blog',
            'news' => '/news',
            'showcase' => '/showcase',
            default => ''
        };

        return $prefix . '/' . $this->slug;
    }

    public function getExcerptAttribute()
    {
        return $this->custom_excerpt ?: Str::limit(strip_tags($this->html), 200);
    }
}
