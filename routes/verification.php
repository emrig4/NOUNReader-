<?php

/**
 * Verification Code Flow Routes
 * 
 * Routes for handling the verification code flow:
 * - Registration sends verification code
 * - User enters code on verification page
 * - If code is valid, user is logged in and redirected to set password
 * - After setting password, user can login normally
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VerificationController;

// Public verification routes (for users who just registered)
Route::middleware(['web'])->group(function () {
    
    // Show verification code entry form
    Route::get('/verify-code', [VerificationController::class, 'showVerificationForm'])
        ->name('verification.form');
    
    // Process verification code submission
    Route::post('/verify-code', [VerificationController::class, 'verifyCode'])
        ->name('verification.verify');
    
    // Resend verification code
    Route::post('/verify-code/resend', [VerificationController::class, 'resendVerificationCode'])
        ->name('verification.resend');
    
    // Set password form (requires authentication)
    Route::get('/set-password', [VerificationController::class, 'showSetPasswordForm'])
        ->name('verification.set-password')
        ->middleware('auth');
    
    // Process password setting
    Route::post('/set-password', [VerificationController::class, 'setPassword'])
        ->name('verification.set-password.post')
        ->middleware('auth');
});

// Redirect route for login flow
Route::get('/verification/redirect/{email}', [VerificationController::class, 'redirectToVerification'])
    ->name('verification.redirect');

// Clean up expired verification records (admin/console only)
Route::get('/verification/cleanup', function () {
    if (!app()->environment('local', 'staging')) {
        abort(404);
    }
    
    $service = app(App\Services\VerificationService::class);
    $service->cleanupExpiredRecords();
    
    return response()->json([
        'success' => true,
        'message' => 'Expired verification records cleaned up successfully'
    ]);
})->middleware(['auth', 'role:admin'])->name('verification.cleanup');

// API route for checking verification status
Route::get('/api/verification/status/{email}', function ($email) {
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }
    
    $verificationService = app(App\Services\VerificationService::class);
    
    return response()->json([
        'user_id' => $user->id,
        'has_set_permanent_password' => $user->has_set_permanent_password,
        'email_verified' => !is_null($user->email_verified_at),
        'code_expired' => $user->isVerificationCodeExpired(),
        'remaining_attempts' => $verificationService->getRemainingAttempts($user),
        'verification_complete' => $verificationService->isVerificationComplete($user),
    ]);
})->name('api.verification.status');
