<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SessionExpiryMiddleware
{
    /**
     * Handle an incoming request.
     * Logs user out after 1 hour of inactivity (change to 5 for testing)
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (Auth::check()) {
            $sessionTimeout = 60; // 1 hour (change to 5 for testing)
            
            // Get last activity time from session
            $lastActivity = Session::get('last_activity_time');
            
            // FIX: If no last activity time set, initialize it (don't log out)
            if (!$lastActivity) {
                // First request after login - set the initial activity time
                Session::put('last_activity_time', time());
                return $next($request);
            }
            
            // Check if session has actually expired
            if ((time() - $lastActivity) > ($sessionTimeout * 60)) {
                // Log user out cleanly
                Auth::logout();
                Session::invalidate();
                Session::regenerateToken();
                
                // Redirect with friendly message
                notify()->warning('Your session has expired. Please login again.');
                return redirect()->route('login');
            }
            
            // Update last activity time for active sessions
            Session::put('last_activity_time', time());
        }
        
        return $next($request);
    }
}