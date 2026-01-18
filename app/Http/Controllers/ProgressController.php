<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\LessonProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    /**
     * Track lesson start
     */
    public function startLesson(Request $request, $lessonSlug)
    {
        $lesson = Post::lessons()
            ->where('slug', $lessonSlug)
            ->published()
            ->firstOrFail();

        $member = auth('member')->user();

        // Get or create enrollment for the course
        $enrollment = null;
        if ($lesson->course_id) {
            $enrollment = $member->enrollments()
                ->where('course_id', $lesson->course_id)
                ->where('status', 'active')
                ->first();

            // Auto-enroll if not enrolled
            if (!$enrollment) {
                $enrollment = $member->enrollIn($lesson->course);
            }
        }

        // Get or create lesson progress
        $progress = LessonProgress::firstOrCreate(
            [
                'member_id' => $member->id,
                'lesson_id' => $lesson->id,
            ],
            [
                'enrollment_id' => $enrollment?->id,
                'status' => 'not_started',
            ]
        );

        $progress->markAsStarted();

        return response()->json([
            'success' => true,
            'progress' => $progress,
        ]);
    }

    /**
     * Update lesson progress
     */
    public function updateProgress(Request $request, $lessonSlug)
    {
        $request->validate([
            'percentage' => 'required|integer|min:0|max:100',
            'time_spent' => 'nullable|integer|min:0',
        ]);

        $lesson = Post::lessons()
            ->where('slug', $lessonSlug)
            ->published()
            ->firstOrFail();

        $member = auth('member')->user();

        $progress = LessonProgress::where('member_id', $member->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrFail();

        $progress->updateProgress(
            $request->percentage,
            $request->input('time_spent', 0)
        );

        return response()->json([
            'success' => true,
            'progress' => $progress->fresh(),
        ]);
    }

    /**
     * Mark lesson as completed
     */
    public function completeLesson(Request $request, $lessonSlug)
    {
        $lesson = Post::lessons()
            ->where('slug', $lessonSlug)
            ->published()
            ->firstOrFail();

        $member = auth('member')->user();

        $progress = LessonProgress::where('member_id', $member->id)
            ->where('lesson_id', $lesson->id)
            ->firstOrFail();

        $progress->markAsCompleted();

        return response()->json([
            'success' => true,
            'message' => 'Lesson completed!',
            'progress' => $progress->fresh(),
        ]);
    }

    /**
     * Get member's progress for a course
     */
    public function getCourseProgress($courseSlug)
    {
        $course = Post::courses()
            ->where('slug', $courseSlug)
            ->published()
            ->firstOrFail();

        $member = auth('member')->user();
        $enrollment = $member->getEnrollment($course);

        if (!$enrollment) {
            return response()->json([
                'enrolled' => false,
                'progress' => 0,
            ]);
        }

        $lessonProgress = LessonProgress::where('enrollment_id', $enrollment->id)
            ->with('lesson')
            ->get();

        return response()->json([
            'enrolled' => true,
            'enrollment' => $enrollment,
            'overall_progress' => $enrollment->progress_percentage,
            'lessons' => $lessonProgress,
        ]);
    }
}
