<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'name', 'slug', 'description',
        'sender_name', 'sender_email', 'sender_reply_to',
        'status', 'subscribe_on_signup'
    ];

    protected $casts = [
        'subscribe_on_signup' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($newsletter) {
            if (empty($newsletter->uuid)) {
                $newsletter->uuid = Str::uuid();
            }
            if (empty($newsletter->slug)) {
                $newsletter->slug = Str::slug($newsletter->name);
            }
        });
    }

    // Relationships
    public function members()
    {
        return $this->belongsToMany(Member::class, 'member_newsletter')
            ->withTimestamps()
            ->withPivot('subscribed_at');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDefaultSignup($query)
    {
        return $query->where('subscribe_on_signup', true);
    }
}
