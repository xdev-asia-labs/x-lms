<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Member extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'uuid', 'email', 'name', 'password',
        'note', 'avatar', 'status', 'subscribed',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'subscribed' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($member) {
            if (empty($member->uuid)) {
                $member->uuid = Str::uuid();
            }
        });
    }

    // Relationships
    public function newsletters()
    {
        return $this->belongsToMany(Newsletter::class, 'member_newsletter')
            ->withTimestamps()
            ->withPivot('subscribed_at');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Post::class, 'course_enrollments', 'member_id', 'course_id')
            ->withPivot('status', 'enrolled_at', 'completed_at', 'progress_percentage')
            ->withTimestamps();
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    // Helpers
    public function isSubscribedTo(Newsletter $newsletter)
    {
        return $this->newsletters()->where('newsletter_id', $newsletter->id)->exists();
    }

    public function subscribeTo(Newsletter $newsletter)
    {
        if (!$this->isSubscribedTo($newsletter)) {
            $this->newsletters()->attach($newsletter->id, [
                'subscribed_at' => now()
            ]);
        }
    }

    public function unsubscribeFrom(Newsletter $newsletter)
    {
        $this->newsletters()->detach($newsletter->id);
    }

    public function isEnrolledIn(Post $course)
    {
        return $this->enrollments()
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();
    }

    public function enrollIn(Post $course)
    {
        if (!$this->isEnrolledIn($course)) {
            return CourseEnrollment::create([
                'member_id' => $this->id,
                'course_id' => $course->id,
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }

        return $this->enrollments()->where('course_id', $course->id)->first();
    }

    public function getEnrollment(Post $course)
    {
        return $this->enrollments()->where('course_id', $course->id)->first();
    }
}
