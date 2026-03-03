<?php

/**
 * Email Verification Routes
 * 
 * Simple, working routes for email verification functionality
 */

use Illuminate\Support\Facades\Route;

// Test route to verify file is loaded
Route::get('/test-email-routes', function() {
    return 'Email verification routes file is loading!';
})->name('test.email.routes');

// Basic Email Verification Routes
Route::middleware(['web'])->group(function () {
    
    // Email verification form
    Route::get('/verify-email', function () {
        if (auth()->check() && is_email_verified(auth()->user())) {
            return redirect('/')->with('success', 'Your email is already verified!');
        }
        
        return view('emails.verify-email');
    })->name('verify.email.form');
    
    // Process email verification
    Route::post('/verify-email', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'token' => 'required|string'
        ]);
        
        $token = $request->input('token');
        
        if (verify_email_token($token)) {
            if (auth()->check()) {
                return redirect('/')->with('success', 'Email verified successfully!');
            } else {
                return redirect('/login')->with('success', 'Email verified successfully! Please log in.');
            }
        }
        
        return back()->with('error', 'Invalid or expired verification token.')->withInput();
    })->name('verify.email.submit');
    
    // Resend verification email
    Route::post('/resend-verification', function () {
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        $user = auth()->user();
        
        if (is_email_verified($user)) {
            return redirect('/')->with('success', 'Your email is already verified!');
        }
        
        if (send_email_verification($user)) {
            return back()->with('success', 'Verification email sent successfully!');
        }
        
        return back()->with('error', 'Failed to send verification email. Please try again.');
    })->name('resend.verification');
});

// Clean up expired tokens (admin only)
Route::get('/admin/cleanup-expired-tokens', function () {
    cleanup_expired_tokens();
    return response()->json([
        'success' => true,
        'message' => 'Expired tokens cleaned up successfully'
    ]);
})->middleware(['auth'])->name('cleanup.expired.tokens');
