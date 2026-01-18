<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\Auth\MemberAuthController;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Blog
Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('show');
});

// Courses
Route::prefix('courses')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::get('/{slug}', [CourseController::class, 'show'])->name('show');
});

// Lessons
Route::prefix('lessons')->name('lessons.')->group(function () {
    Route::get('/{slug}', [LessonController::class, 'show'])->name('show');
});

// Member Authentication Routes (Guest only)
Route::middleware('guest:member')->prefix('member')->name('member.')->group(function () {
    // Login
    Route::get('/login', [MemberAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [MemberAuthController::class, 'login'])->name('login.post');
    
    // Register
    Route::get('/register', [MemberAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [MemberAuthController::class, 'register'])->name('register.post');
    
    // Password Reset
    Route::get('/password/reset', [MemberAuthController::class, 'showResetRequestForm'])->name('password.request');
    Route::post('/password/email', [MemberAuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [MemberAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [MemberAuthController::class, 'reset'])->name('password.update');
});

// Member Email Verification Routes
Route::prefix('member')->name('member.')->group(function () {
    Route::get('/email/verify', [MemberAuthController::class, 'showVerificationNotice'])
        ->middleware('auth:member')
        ->name('verification.notice');
    
    Route::get('/email/verify/{id}/{hash}', [MemberAuthController::class, 'verify'])
        ->middleware(['auth:member', 'signed'])
        ->name('verification.verify');
    
    Route::post('/email/resend', [MemberAuthController::class, 'resendVerificationEmail'])
        ->middleware(['auth:member', 'throttle:6,1'])
        ->name('verification.resend');
});

// Member Routes (requires auth:member)
Route::middleware('auth:member')->group(function () {
    // Logout
    Route::post('/member/logout', [MemberAuthController::class, 'logout'])->name('member.logout');
    
    // Dashboard
    Route::get('/member/dashboard', function () {
        return view('member.dashboard');
    })->name('member.dashboard');
    
    // Enrollments
    Route::prefix('enrollments')->name('enrollments.')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('index');
        Route::post('/courses/{courseSlug}', [EnrollmentController::class, 'store'])->name('store');
        Route::delete('/{enrollmentId}', [EnrollmentController::class, 'destroy'])->name('destroy');
    });

    // Progress Tracking
    Route::prefix('progress')->name('progress.')->group(function () {
        Route::post('/lessons/{lessonSlug}/start', [ProgressController::class, 'startLesson'])->name('start');
        Route::put('/lessons/{lessonSlug}', [ProgressController::class, 'updateProgress'])->name('update');
        Route::post('/lessons/{lessonSlug}/complete', [ProgressController::class, 'completeLesson'])->name('complete');
        Route::get('/courses/{courseSlug}', [ProgressController::class, 'getCourseProgress'])->name('course');
    });
});

// Tags
Route::get('/tag/{slug}', function ($slug) {
    $tag = \App\Models\Tag::where('slug', $slug)->firstOrFail();
    $posts = $tag->posts()->published()->with(['tags', 'authors'])->paginate(12);
    return view('tag.show', compact('tag', 'posts'));
})->name('tag.show');

// Authors
Route::get('/author/{slug}', function ($slug) {
    $author = \App\Models\User::where('slug', $slug)->firstOrFail();
    $posts = $author->posts()->published()->with(['tags', 'authors'])->paginate(12);
    return view('author.show', compact('author', 'posts'));
})->name('author.show');


