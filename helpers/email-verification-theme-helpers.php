<?php

/**
 * Email Verification Theme Helper Functions
 * 
 * This file contains helper functions for email verification functionality
 * that works with theme-based implementations without modifying core login/registration forms.
 */

if (!function_exists('send_email_verification')) {
    /**
     * Send email verification to user
     */
    function send_email_verification($user, $token = null)
    {
        if (!$token) {
            $token = generate_email_verification_token($user);
        }
        
        // Generate verification URL
        $verificationUrl = url('/verify-email?token=' . $token);
        
        // Send verification email
        try {
            Mail::send('emails.verify-email', [
                'user' => $user,
                'verificationUrl' => $verificationUrl,
                'token' => $token
            ], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Verify Your Email Address');
            });
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Email verification sending failed: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('generate_email_verification_token')) {
    /**
     * Generate email verification token for user
     */
    function generate_email_verification_token($user)
    {
        $token = Str::random(60);
        
        // Store token in database (assuming user model has this field)
        $user->email_verification_token = $token;
        $user->save();
        
        return $token;
    }
}

if (!function_exists('verify_email_token')) {
    /**
     * Verify email token and mark user as verified
     */
    function verify_email_token($token)
    {
        if (!$token) {
            return false;
        }
        
        // Find user by token
        $user = \App\Models\User::where('email_verification_token', $token)->first();
        
        if ($user) {
            // Mark as verified
            $user->email_verified = true;
            $user->email_verification_token = null;
            $user->email_verified_at = now();
            $user->save();
            
            return true;
        }
        
        return false;
    }
}

if (!function_exists('is_email_verified')) {
    /**
     * Check if user email is verified
     */
    function is_email_verified($user)
    {
        return $user->email_verified_at !== null || $user->email_verified === true;
    }
}

if (!function_exists('send_login_verification')) {
    /**
     * Send login verification code
     */
    function send_login_verification($user, $token = null)
    {
        if (!$token) {
            $token = rand(100000, 999999); // 6-digit code
        }
        
        // Store login token with expiration
        $user->login_token = $token;
        $user->token_expires_at = now()->addMinutes(15);
        $user->save();
        
        // Send verification code via email or SMS
        try {
            Mail::send('emails.verify-login', [
                'user' => $user,
                'code' => $token
            ], function($message) use ($user) {
                $message->to($user->email);
                $message->subject('Login Verification Code');
            });
            
            return $token;
        } catch (\Exception $e) {
            \Log::error('Login verification sending failed: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('verify_login_token')) {
    /**
     * Verify login token
     */
    function verify_login_token($user, $token)
    {
        if (!$user || !$token) {
            return false;
        }
        
        // Check if token matches and is not expired
        if ($user->login_token === $token && $user->token_expires_at && $user->token_expires_at->isFuture()) {
            // Update last login and clear token
            $user->last_login_at = now();
            $user->login_token = null;
            $user->token_expires_at = null;
            $user->save();
            
            return true;
        }
        
        return false;
    }
}

if (!function_exists('cleanup_expired_tokens')) {
    /**
     * Clean up expired tokens
     */
    function cleanup_expired_tokens()
    {
        // Remove expired login tokens
        \App\Models\User::where('token_expires_at', '<', now())
            ->update([
                'login_token' => null,
                'token_expires_at' => null
            ]);
    }
}

if (!function_exists('get_email_verification_stats')) {
    /**
     * Get email verification statistics
     */
    function get_email_verification_stats()
    {
        $totalUsers = \App\Models\User::count();
        $verifiedUsers = \App\Models\User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = $totalUsers - $verifiedUsers;
        
        return [
            'total' => $totalUsers,
            'verified' => $verifiedUsers,
            'unverified' => $unverifiedUsers,
            'verification_rate' => $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 2) : 0
        ];
    }
}

/**
 * Theme-specific hooks and filters
 */

// Add email verification requirements for theme developers
if (!function_exists('theme_requires_email_verification')) {
    function theme_requires_email_verification()
    {
        return config('email_verification.require_verification', false);
    }
}

// Add email verification check for theme templates
if (!function_exists('theme_email_verification_check')) {
    function theme_email_verification_check($user)
    {
        if (theme_requires_email_verification() && !is_email_verified($user)) {
            return redirect('/verify-email')->with('warning', 'Please verify your email address before accessing this page.');
        }
        return null;
    }
}
