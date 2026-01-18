<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\NewsletterController;
use Illuminate\Support\Facades\Route;

// Public Content API (similar to Ghost Content API)
Route::prefix('api')->group(function () {
    
    // Posts API
    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{slug}', [PostController::class, 'show']);
    Route::get('/posts/search', [PostController::class, 'search']);
    
    // Lessons API (for AJAX loading in course pages)
    Route::get('/courses/{courseId}/lessons', [PostController::class, 'lessons']);
    
    // Tags API
    Route::get('/tags', [TagController::class, 'index']);
    Route::get('/tags/{slug}', [TagController::class, 'show']);
    
    // Newsletter API
    Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
    Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);
});
