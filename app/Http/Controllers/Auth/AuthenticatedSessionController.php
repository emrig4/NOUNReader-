<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        if (Auth::check()) {
            return redirect('/account/subscription')
                ->with('info', 'You are already logged in.');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Custom authentication for users with verification codes
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            Log::warning('Login attempt with non-existent email', [
                'email' => $request->email,
                'ip' => $request->ip()
            ]);
            
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if user has set permanent password
        if (!$user->hasSetPermanentPassword()) {
            Log::warning('Login attempt by user without permanent password', [
                'user_id' => $user->id,
                'email' => $request->email
            ]);
            
            return redirect()
                ->route('verification.show-verify-form')
                ->with('error', 'Please verify your email first before logging in.')
                ->with('email', $request->email);
        }

        // Check if account is locked (implement if needed)
        // if ($user->isLocked()) {
        //     throw ValidationException::withMessages([
        //         'email' => ['This account is temporarily locked.'],
        //     ]);
        // }

        // Attempt to authenticate
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            Log::warning('Failed login attempt', [
                'user_id' => $user->id,
                'email' => $request->email,
                'ip' => $request->ip()
            ]);
            
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $request->session()->regenerate();
        
        Log::info('User logged in successfully', [
            'user_id' => Auth::id(),
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Clear any failed login attempts for this user
        // RateLimiter::clear('login-attempt:'.$user->email);

        $intendedUrl = session('url.intended');
        $dashboardUrl = '/account/subscription';

        return redirect($intendedUrl ?: $dashboardUrl)
            ->with('success', 'Welcome back! You are now logged in.');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            Log::info('User logged out successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip()
            ]);
        }

        return redirect('/')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show logout confirmation page (if needed)
     */
    public function showLogoutConfirm()
    {
        return view('auth.logout-confirm');
    }

    /**
     * Handle logout confirmation
     */
    public function confirmLogout(Request $request)
    {
        if ($request->input('confirm') === 'yes') {
            return $this->destroy($request);
        }

        return redirect()->back()->with('info', 'Logout cancelled.');
    }
}