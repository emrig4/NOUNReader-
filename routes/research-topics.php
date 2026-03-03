<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResearchTopicController;

/*
|--------------------------------------------------------------------------
| Research Topics Suggestion Routes
|--------------------------------------------------------------------------
*/

// Serve the main page - now calls controller method
Route::get('/topics-suggestion', [ResearchTopicController::class, 'showForm'])
    ->name('topics.suggestion');

// API Routes (all POST-only as per requirements)
Route::prefix('api/research-topics')->group(function () {
    
    // Get departments with autocomplete
    Route::post('/departments', [ResearchTopicController::class, 'getDepartments'])
        ->name('api.topics.departments');
    
    // Get topic suggestions based on department and work type
    Route::post('/suggestions', [ResearchTopicController::class, 'getSuggestions'])
        ->name('api.topics.suggestions');
    
    // Save user interaction for analytics
    Route::post('/save-interaction', [ResearchTopicController::class, 'saveInteraction'])
        ->name('api.topics.save-interaction');
    
    // Get analytics data (for admin purposes)
    Route::post('/analytics', [ResearchTopicController::class, 'getAnalytics'])
        ->name('api.topics.analytics');
    
    // Get popular topics based on user interactions
    Route::post('/popular-topics', [ResearchTopicController::class, 'getPopularTopics'])
        ->name('api.topics.popular-topics');
});