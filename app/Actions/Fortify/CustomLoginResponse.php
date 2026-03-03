<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = Auth::user();
        
        // 2022 STYLE: No verification check - direct dashboard access
        if ($user && $user->has_set_permanent_password) {
            return redirect()->intended('/dashboard');
        }
        
        // Default redirect
        return redirect()->intended('/dashboard');
    }
}