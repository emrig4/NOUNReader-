<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use App\Mail\WelcomeVerifiedUserWithCreditMail;
use App\Services\AutoCreditService;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     * When user clicks verification link, auto-verify their email
     */
    public function create(Request $request, $token)
    {
        if (Auth::check()) {
            return redirect('/account/subscription')
                ->with('info', 'You are already logged in.');
        }

        $email = $request->get('email');
        
        if ($email) {
            $user = \App\Models\User::where('email', $email)->first();
            
            if ($user && is_null($user->email_verified_at)) {
                // Auto-verify the user's email when they visit the verification link (FIRST TIME)
                try {
                    $user->email_verified_at = now();
                    $user->status = 'active';
                    $user->save();
                    
                    \Log::info('Email auto-verified via verification link (first time)', [
                        'user_id' => $user->id, 
                        'email' => $email
                    ]);
                    
                    // Send welcome email right after verification (when they see verified message)
                    try {
                        $autoCreditService = new AutoCreditService();
                        $creditAmount = $autoCreditService->getAutoCreditAmount();
                        
                        // Safely get wallet balance
                        $walletBalance = 190.00;
                        if ($user->CreditWallet) {
                            $walletBalance = $user->CreditWallet->balance ?? 190.00;
                        }
                        
                        $welcomeEmail = new WelcomeVerifiedUserWithCreditMail(
                            $user,
                            $creditAmount ?? 190.00,
                            $walletBalance,
                            now()
                        );
                        
                        Mail::to($user->email)->send($welcomeEmail);
                        
                        \Log::info('Welcome email sent after verification', ['user_id' => $user->id]);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send welcome email after verification: ' . $e->getMessage());
                    }
                    
                    // Show success message with Login button (first time verification)
                    return view('auth.reset-password', [
                        'email' => $email,
                        'token' => $token,
                        'verified' => true,
                        'isFirstVerification' => true
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Failed to auto-verify email: ' . $e->getMessage());
                }
            } elseif ($user && !is_null($user->email_verified_at)) {
                // Already verified user - show password reset form (forgot password flow)
                return view('auth.reset-password', [
                    'email' => $email,
                    'token' => $token,
                    'verified' => true,
                    'isFirstVerification' => false
                ]);
            }
        }

        // Show normal reset password form (for forgot password flow or invalid)
        return view('auth.reset-password', [
            'email' => $email ?? $request->get('email'),
            'token' => $token,
            'verified' => false,
            'isFirstVerification' => false
        ]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verify the reset token
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => ['Invalid reset token or email address.'],
            ]);
        }

        // Check if this is a fresh verification (first time) or forgot password
        $isFirstVerification = $request->has('is_verification') && $request->is_verification == 'true';

        // Here we will attempt to reset the user's password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request, $isFirstVerification) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                    'has_set_permanent_password' => true,
                ]);
                
                // If this is a fresh verification, mark email as verified
                if ($isFirstVerification && is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                    $user->status = 'active';
                }
                
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            if ($isFirstVerification) {
                // First time verification already happened in create() method when they clicked the link
                // Just redirect to login
                return redirect()->route('login')
                    ->with('success', 'Email verified successfully! You can now log in.');
            } else {
                // Regular password reset - user already had verified email (FORGOT PASSWORD)
                
                return redirect()->route('login')
                    ->with('success', 'Password reset successfully! You can now log in with your new password.');
            }
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}