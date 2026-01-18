<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'profile_image',
        'cover_image',
        'bio',
        'website',
        'location',
        'facebook',
        'twitter',
        'accessibility',
        'status',
        'visibility',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            if (empty($user->slug) && !empty($user->name)) {
                $user->slug = Str::slug($user->name);
            }
        });
    }

    // Relationships
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_user')
            ->withPivot('sort_order')
            ->orderBy('sort_order');
    }

    public function courses()
    {
        return $this->posts()->where('post_type', 'course');
    }

    public function articles()
    {
        return $this->posts()->where('post_type', 'blog');
    }

    // Accessors
    public function getUrlAttribute()
    {
        return '/author/' . $this->slug;
    }

    // Helpers
    public function isAuthorOf(Post $post)
    {
        return $this->posts()->where('posts.id', $post->id)->exists();
    }
}
