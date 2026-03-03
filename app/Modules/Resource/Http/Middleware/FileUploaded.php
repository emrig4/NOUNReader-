<?php

namespace App\Modules\Resource\Http\Middleware;

use Closure;
use App\Modules\File\Models\TemporaryFile;


class FileUploaded
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         // get the last temp file from session
        $sessionId = session()->getId();
        $file = TemporaryFile::where('session_id', $sessionId)->latest()->first();
        if(!$file){
            return redirect()->route('resources.create.upload')->with('error_message', 'Please upload a file');
        }
        return $next($request);
    }
}
