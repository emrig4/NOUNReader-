<?php

namespace App\Http\Middleware;

use App\Services\VerificationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginVerificationMiddleware
{
    protected $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Only process after authentication attempt
        if (!$request->has('password') || !Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return $next($request);
        }

        $user = Auth::user();
        
        // If user already has permanent password, proceed normally
        if ($user->has_set_permanent_password) {
            return $next($request);
        }

        // Check if user has verification code (temporary password)
        $isTemporaryPassword = $this->isTemporaryPassword($user);
        
        if ($isTemporaryPassword) {
            // Log the user out temporarily and redirect to verification
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Store email in session for verification
            session(['verification_email' => $user->email]);
            
            Log::info('User redirected to verification (temporary password detected)', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return redirect()->route('verification.form')
                ->with('info', 'Please verify your email with the code we sent you.');
        }

        // If user is authenticated but hasn't set permanent password
        // (This handles the case where user verified code but hasn't set password yet)
        if (!$user->has_set_permanent_password && $user->email_verified_at) {
            // User is already logged in, redirect to set password
            return redirect()->route('verification.set-password')
                ->with('success', 'Please set your permanent password to continue.');
        }

        return $next($request);
    }

    /**
     * Check if user's password is a temporary verification code
     */
    private function isTemporaryPassword($user): bool
    {
        // A password is considered temporary if:
        // 1. User hasn't set permanent password
        // 2. User doesn't have email verified yet
        // 3. Verification code is not expired
        
        return !$user->has_set_permanent_password && 
               !$user->email_verified_at && 
               !$user->isVerificationCodeExpired();
    }
}