<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceHttps
{
    /**
     * Handle an incoming request.
     * Force HTTPS for all requests if not already using HTTPS
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the request is not over HTTPS
        if (!$request->isSecure()) {
            // Build the HTTPS URL
            $httpsUrl = 'https://' . $request->getHost() . $request->getRequestUri();
            
            // Redirect to HTTPS with 301 (permanent redirect) for SEO
            return redirect($httpsUrl, 301);
        }

        return $next($request);
    }
}