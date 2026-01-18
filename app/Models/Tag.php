<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description',
        'feature_image', 'accent_color', 'visibility',
        'meta_title', 'meta_description',
        'og_image', 'og_title', 'og_description',
        'twitter_image', 'twitter_title', 'twitter_description'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // Relationships
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_tag')
            ->withPivot('sort_order')
            ->orderBy('published_at', 'desc');
    }

    public function courses()
    {
        return $this->posts()->where('post_type', 'course');
    }

    // Accessors
    public function getUrlAttribute()
    {
        return '/tag/' . $this->slug;
    }

    public function getPostCountAttribute()
    {
        return $this->posts()->published()->count();
    }

    public function getCourseCountAttribute()
    {
        return $this->courses()->published()->count();
    }
}
