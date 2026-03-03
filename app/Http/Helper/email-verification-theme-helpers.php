<?php

/**
 * Email Verification Theme Helper Functions
 * 
 * These functions enable email verification through theme-only implementations
 * without modifying existing forms or layouts.
 */

// =============================================================================
// EMAIL VERIFICATION HELPER FUNCTIONS
// =============================================================================

if (!function_exists('isEmailVerified')) {
    /**
     * Check if user's email is verified
     * 
     * @param int|null $userId Optional user ID, defaults to current authenticated user
     * @return bool
     */
    function isEmailVerified($userId = null)
    {
        $user = $userId 
            ? \App\Models\User::find($userId) 
            : auth()->user();
            
        return $user && $user->hasVerifiedEmail();
    }
}

if (!function_exists('canAccessWithoutEmailVerification')) {
    /**
     * Check if user can access without email verification (role-based)
     * 
     * @param \App\Models\User|null $user
     * @return bool
     */
    function canAccessWithoutEmailVerification($user = null)
    {
        $user = $user ?: auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // Check for bypass roles in settings
        $bypassRoles = explode(',', setting('email_verification_bypass_roles', 'sudo,admin'));
        
        // Check if user has any of the bypass roles
        foreach ($user->getRoleNames() as $role) {
            if (in_array(trim($role), $bypassRoles)) {
                return true;
            }
        }
        
        // Check for individual user bypass
        if ($user->email_verification_bypass) {
            return true;
        }
        
        return false;
    }
}

if (!function_exists('needsEmailVerification')) {
    /**
     * Check if user needs email verification
     * 
     * @param \App\Models\User|null $user
     * @return bool
     */
    function needsEmailVerification($user = null)
    {
        $user = $user ?: auth()->user();
        
        if (!$user) {
            return false;
        }
        
        // If email is already verified, no need
        if ($user->hasVerifiedEmail()) {
            return false;
        }
        
        // Check if bypass is allowed
        if (canAccessWithoutEmailVerification($user)) {
            return false;
        }
        
        // Check if email verification is enabled
        return setting('enable_email_verification', true);
    }
}

if (!function_exists('sendEmailVerificationNotification')) {
    /**
     * Send email verification notification to user
     * 
     * @param \App\Models\User $user
     * @return bool
     */
    function sendEmailVerificationNotification($user)
    {
        try {
            $user->sendEmailVerificationNotification();
            return true;
        } catch (\Exception $e) {
            \Log::error('Email verification notification failed', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

if (!function_exists('generateEmailVerificationUrl')) {
    /**
     * Generate email verification URL for user
     * 
     * @param \App\Models\User $user
     * @return string
     */
    function generateEmailVerificationUrl($user)
    {
        return \URL::temporarySignedRoute(
            'verification.verify',
            \Illuminate\Support\Carbon::now()->addMinutes(\Illuminate\Support\Facades\Config::get('auth.verification.expire', 60)),
            ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
        );
    }
}

// =============================================================================
// ADMIN EMAIL VERIFICATION MANAGEMENT FUNCTIONS
// =============================================================================

if (!function_exists('getEmailVerificationStats')) {
    /**
     * Get email verification statistics for admin dashboard
     * 
     * @return array
     */
    function getEmailVerificationStats()
    {
        $totalUsers = \App\Models\User::count();
        $verifiedUsers = \App\Models\User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = $totalUsers - $verifiedUsers;
        $pendingResend = \App\Models\User::whereNull('email_verified_at')
            ->where('created_at', '>', now()->subDays(7))
            ->count();
            
        return [
            'total_users' => $totalUsers,
            'verified_users' => $verifiedUsers,
            'unverified_users' => $unverifiedUsers,
            'pending_resend' => $pendingResend,
            'verification_rate' => $totalUsers > 0 ? round(($verifiedUsers / $totalUsers) * 100, 2) : 0
        ];
    }
}

if (!function_exists('forceVerifyUserEmail')) {
    /**
     * Force verify user's email (admin function)
     * 
     * @param int $userId
     * @return bool
     */
    function forceVerifyUserEmail($userId)
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            $user->markEmailAsVerified();
            
            \Log::info('Email verification forced by admin', [
                'admin_user_id' => auth()->id(),
                'verified_user_id' => $userId,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to force email verification', [
                'admin_user_id' => auth()->id(),
                'target_user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

if (!function_exists('unverifyUserEmail')) {
    /**
     * Unverify user's email (admin function)
     * 
     * @param int $userId
     * @return bool
     */
    function unverifyUserEmail($userId)
    {
        try {
            $user = \App\Models\User::findOrFail($userId);
            $user->email_verified_at = null;
            $user->save();
            
            \Log::info('Email verification removed by admin', [
                'admin_user_id' => auth()->id(),
                'unverified_user_id' => $userId,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to unverify email', [
                'admin_user_id' => auth()->id(),
                'target_user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

if (!function_exists('bulkSendVerificationEmails')) {
    /**
     * Send verification emails to unverified users
     * 
     * @param array $userIds Optional specific user IDs
     * @return array
     */
    function bulkSendVerificationEmails($userIds = null)
    {
        $query = \App\Models\User::whereNull('email_verified_at');
        
        if ($userIds) {
            $query->whereIn('id', $userIds);
        }
        
        $users = $query->get();
        $sent = 0;
        $failed = 0;
        
        foreach ($users as $user) {
            if (sendEmailVerificationNotification($user)) {
                $sent++;
            } else {
                $failed++;
            }
        }
        
        \Log::info('Bulk email verification sent', [
            'admin_user_id' => auth()->id(),
            'total_users' => $users->count(),
            'sent' => $sent,
            'failed' => $failed
        ]);
        
        return [
            'total' => $users->count(),
            'sent' => $sent,
            'failed' => $failed
        ];
    }
}

// =============================================================================
// THEME UI HELPER FUNCTIONS
// =============================================================================

if (!function_exists('getEmailVerificationStatusBadge')) {
    /**
     * Get email verification status badge HTML
     * 
     * @param \App\Models\User $user
     * @return string
     */
    function getEmailVerificationStatusBadge($user)
    {
        if ($user->hasVerifiedEmail()) {
            return '<span class="badge badge-success"><i class="fa fa-check"></i> Verified</span>';
        } elseif (canAccessWithoutEmailVerification($user)) {
            return '<span class="badge badge-warning"><i class="fa fa-shield"></i> Bypassed</span>';
        } else {
            return '<span class="badge badge-danger"><i class="fa fa-exclamation-triangle"></i> Unverified</span>';
        }
    }
}

if (!function_exists('getEmailVerificationNotice')) {
    /**
     * Get email verification notice for unverified users
     * 
     * @return string|null
     */
    function getEmailVerificationNotice()
    {
        if (!auth()->check() || !needsEmailVerification()) {
            return null;
        }
        
        $user = auth()->user();
        $verifyUrl = route('verification.notice');
        $resendUrl = route('verification.send');
        
        $html = '<div class="alert alert-warning email-verification-notice">';
        $html .= '<div class="d-flex align-items-center">';
        $html .= '<div class="mr-3">';
        $html .= '<i class="fa fa-exclamation-triangle text-warning fa-2x"></i>';
        $html .= '</div>';
        $html .= '<div class="flex-grow-1">';
        $html .= '<h5 class="alert-heading">Email Verification Required</h5>';
        $html .= '<p class="mb-2">Please verify your email address to continue using all features.</p>';
        $html .= '<p class="small text-muted mb-2">We sent a verification link to: <strong>' . $user->email . '</strong></p>';
        $html .= '<div class="d-flex gap-2">';
        $html .= '<a href="' . $verifyUrl . '" class="btn btn-sm btn-warning">Resend Email</a>';
        $html .= '<a href="' . $resendUrl . '" class="btn btn-sm btn-outline-warning">Resend Verification</a>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('shouldShowEmailVerificationModal')) {
    /**
     * Check if email verification modal should be shown
     * 
     * @return bool
     */
    function shouldShowEmailVerificationModal()
    {
        return auth()->check() && 
               needsEmailVerification() && 
               !session()->has('email_verification_notice_shown');
    }
}

// =============================================================================
// SETTINGS HELPER FUNCTIONS
// =============================================================================

if (!function_exists('isEmailVerificationEnabled')) {
    /**
     * Check if email verification is enabled in settings
     * 
     * @return bool
     */
    function isEmailVerificationEnabled()
    {
        return setting('enable_email_verification', true);
    }
}

if (!function_exists('getEmailVerificationBypassRoles')) {
    /**
     * Get roles that can bypass email verification
     * 
     * @return array
     */
    function getEmailVerificationBypassRoles()
    {
        return explode(',', setting('email_verification_bypass_roles', 'sudo,admin'));
    }
}

if (!function_exists('isEmailVerificationRequiredForRegistration')) {
    /**
     * Check if email verification is required during registration
     * 
     * @return bool
     */
    function isEmailVerificationRequiredForRegistration()
    {
        return setting('require_email_verification_on_register', true);
    }
}

// =============================================================================
// COMPATIBILITY FUNCTIONS FOR ROUTES (snake_case)
// =============================================================================

if (!function_exists('is_email_verified')) {
    /**
     * Check if user's email is verified (snake_case for routes compatibility)
     * 
     * @param \App\Models\User|null $user Optional user, defaults to current authenticated user
     * @return bool
     */
    function is_email_verified($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }
        
        return $user ? $user->email_verified === 1 : false;
    }
}

if (!function_exists('send_email_verification')) {
    /**
     * Send email verification to user (snake_case for routes compatibility)
     * 
     * @param \App\Models\User $user
     * @param string $verificationUrl Optional custom verification URL
     * @return bool
     */
    function send_email_verification($user, $verificationUrl = null)
    {
        if ($user->email_verified === 1) {
            return true; // Already verified
        }
        
        // Generate verification token
        $token = Illuminate\Support\Str::random(64);
        $user->email_verification_token = $token;
        $user->save();
        
        // Generate verification URL
        if (!$verificationUrl) {
            $verificationUrl = route('verify.email.submit', ['token' => $token]);
        }
        
        // Send email notification
        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\VerifyEmail($user, $verificationUrl));
            return true;
        } catch (Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('verify_email_token')) {
    /**
     * Verify email token (snake_case for routes compatibility)
     * 
     * @param string $token
     * @return bool
     */
    function verify_email_token($token)
    {
        $user = \App\Models\User::where('email_verification_token', $token)->first();
        
        if (!$user) {
            return false;
        }
        
        // Mark email as verified
        $user->email_verified = 1;
        $user->email_verification_token = null;
        $user->save();
        
        return true;
    }
}

if (!function_exists('cleanup_expired_tokens')) {
    /**
     * Clean up expired verification tokens
     * 
     * @return int Number of tokens cleaned up
     */
    function cleanup_expired_tokens()
    {
        $expiry = now()->subDays(7); // 7 days expiry
        
        return \App\Models\User::where('email_verification_token', '!=', null)
            ->where('updated_at', '<', $expiry)
            ->update(['email_verification_token' => null]);
    }
}