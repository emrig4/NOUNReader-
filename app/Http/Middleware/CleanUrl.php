<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleanUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ( str_contains($request->getRequestUri(), 'public/') ) {

        //Remove .php from the request url
        $url = str_replace('public/', '', $request->url() );

        return redirect($url);
        }

        return $next($request);
    }
}
