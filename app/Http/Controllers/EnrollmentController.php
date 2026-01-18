<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    /**
     * Show member's enrolled courses
     */
    public function index()
    {
        $member = auth('member')->user();
        $enrollments = $member->enrollments()
            ->with('course')
            ->orderBy('enrolled_at', 'desc')
            ->paginate(12);

        return view('member.enrollments', compact('enrollments'));
    }

    /**
     * Enroll in a course
     */
    public function store(Request $request, $courseSlug)
    {
        $course = Post::courses()
            ->where('slug', $courseSlug)
            ->published()
            ->firstOrFail();

        $member = auth('member')->user();

        if ($member->isEnrolledIn($course)) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        $enrollment = $member->enrollIn($course);

        return redirect()
            ->route('courses.show', $courseSlug)
            ->with('success', 'Successfully enrolled in the course!');
    }

    /**
     * Cancel enrollment
     */
    public function destroy($enrollmentId)
    {
        $member = auth('member')->user();
        $enrollment = CourseEnrollment::where('id', $enrollmentId)
            ->where('member_id', $member->id)
            ->firstOrFail();

        $enrollment->cancel();

        return back()->with('success', 'Enrollment cancelled successfully.');
    }
}
