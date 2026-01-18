<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    use HasFactory;

    protected $table = 'lesson_progress';

    protected $fillable = [
        'member_id',
        'lesson_id',
        'enrollment_id',
        'status',
        'progress_percentage',
        'time_spent',
        'started_at',
        'completed_at',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Post::class, 'lesson_id');
    }

    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    // Helpers
    public function markAsStarted()
    {
        if ($this->status === 'not_started') {
            $this->update([
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'progress_percentage' => 100,
            'completed_at' => now(),
        ]);

        // Update course enrollment progress
        $this->updateCourseProgress();
    }

    public function updateProgress($percentage, $timeSpent = 0)
    {
        $this->increment('time_spent', $timeSpent);
        
        $this->update([
            'progress_percentage' => min(100, max(0, $percentage)),
            'status' => $percentage >= 100 ? 'completed' : 'in_progress',
            'completed_at' => $percentage >= 100 ? now() : null,
        ]);

        if ($percentage >= 100) {
            $this->updateCourseProgress();
        }
    }

    protected function updateCourseProgress()
    {
        if (!$this->enrollment_id) {
            return;
        }

        $enrollment = $this->enrollment;
        $course = $enrollment->course;
        $totalLessons = $course->lessons()->count();
        
        if ($totalLessons === 0) {
            return;
        }

        $completedLessons = LessonProgress::where('enrollment_id', $this->enrollment_id)
            ->where('status', 'completed')
            ->count();

        $courseProgress = ($completedLessons / $totalLessons) * 100;
        $enrollment->updateProgress($courseProgress);
    }
}
