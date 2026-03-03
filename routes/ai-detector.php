<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AiDetectorController;

/*
|--------------------------------------------------------------------------
| AI Detector Routes
|--------------------------------------------------------------------------
*/

// Web Routes
Route::middleware(['web'])->group(function () {
    // Main AI detector interface
    Route::get('/ai-detector', [AiDetectorController::class, 'index'])
        ->name('ai-detector.index');
    
    // Perform AI detection
    Route::post('/ai-detector/check', [AiDetectorController::class, 'check'])
        ->name('ai-detector.check');
    
    // Detection history
    Route::get('/ai-detector/history', [AiDetectorController::class, 'history'])
        ->name('ai-detector.history');
    
    // Download detection report
    Route::get('/ai-detector/report/{id}', [AiDetectorController::class, 'downloadReport'])
        ->name('ai-detector.report');
});

// API Routes (for AJAX and external integration)
Route::prefix('ai-detector/api')->middleware(['api'])->group(function () {
    // AJAX detection endpoint
    Route::post('/check', [AiDetectorController::class, 'apiCheck'])
        ->name('ai-detector.api.check');
    
    // AJAX history endpoint
    Route::get('/history', [AiDetectorController::class, 'apiHistory'])
        ->name('ai-detector.api.history');
    
    // Detection statistics
    Route::get('/stats', [AiDetectorController::class, 'apiStats'])
        ->name('ai-detector.api.stats');
});

// Public API Routes (for external integrations)
Route::prefix('api/v1/ai-detector')->middleware(['api'])->group(function () {
    Route::post('/detect', [AiDetectorController::class, 'publicDetect'])
        ->name('api.ai-detector.detect');
    
    Route::get('/detection/{id}', [AiDetectorController::class, 'publicGetDetection'])
        ->name('api.ai-detector.get');
});