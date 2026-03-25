<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        if (Auth::check()) {
            return redirect('/account/subscription')
                ->with('info', 'You are already logged in.');
        }

        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if user exists and has set permanent password
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user || !$user->hasSetPermanentPassword()) {
            // Don't reveal whether user exists or not for security
            return back()
                ->withInput($request->only('email'))
                ->with('status', 'If an account with that email exists and has a permanent password set, we have emailed a password reset link.');
        }

        // Custom branded password reset email implementation
        try {
            // Create reset token
            $token = app('auth.password.broker')->createToken($user);
            $actionUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
            
            // Send custom branded email with readprojecttopics template
            Mail::to($user->email)->send(new PasswordResetMail($user, $actionUrl));
            
            return back()->with('status', 'We have emailed your password reset link!');
            
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Password reset email failed: ' . $e->getMessage());
            
            throw ValidationException::withMessages([
                'email' => ['We could not send the password reset email. Please try again.'],
            ]);
        }
    }
}