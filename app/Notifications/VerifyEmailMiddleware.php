<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class VerifyEmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        // Allow if user is not authenticated
        if (!$user) {
            return $next($request);
        }
        
        // Allow if email is already verified
        if ($user->hasVerifiedEmail()) {
            return $next($request);
        }
        
        // Check if user can bypass email verification
        if ($this->canBypassEmailVerification($user)) {
            return $next($request);
        }
        
        // Check if email verification is enabled
        if (!setting('enable_email_verification', true)) {
            return $next($request);
        }
        
        // Get paths that don't require email verification
        $allowedPaths = [
            'email/verify',
            'email/verification-notice',
            'email/resend',
            'logout',
            'user/profile',
            'account/profile',
            'api/email',
            'api/email/resend'
        ];
        
        $currentPath = $request->path();
        
        // Check if current path is allowed
        foreach ($allowedPaths as $allowedPath) {
            if (str_contains($currentPath, $allowedPath)) {
                return $next($request);
            }
        }
        
        // Redirect to email verification notice
        return $request->expectsJson()
            ? abort(403, 'Your email address is not verified.')
            : Redirect::route('verification.notice');
    }
    
    /**
     * Check if user can bypass email verification based on roles or settings
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    private function canBypassEmailVerification($user)
    {
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