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
     */
    public function create(Request $request, $token)
    {
        if (Auth::check()) {
            return redirect('/account/subscription')
                ->with('info', 'You are already logged in.');
        }

        return view('auth.reset-password', [
            'email' => $request->email,
            'token' => $token
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

        // Check if this is a new user verification (email not verified yet)
        $isNewUserVerification = is_null($user->email_verified_at);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request, $isNewUserVerification) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                    'has_set_permanent_password' => true, // Ensure this is set
                ]);
                
                // If this is a new user verification, mark email as verified
                if ($isNewUserVerification) {
                    $user->email_verified_at = now();
                    $user->status = 'active';
                }
                
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            if ($isNewUserVerification) {
                // New user verified their email via password reset link
                // Send welcome email after verification
                try {
                    $autoCreditService = new AutoCreditService();
                    $creditAmount = $autoCreditService->getAutoCreditAmount();
                    
                    $welcomeEmail = new WelcomeVerifiedUserWithCreditMail(
                        $user,
                        $creditAmount ?? 190.00,
                        $user->CreditWallet->balance ?? $creditAmount ?? 190.00,
                        now()
                    );
                    
                    Mail::to($user->email)->send($welcomeEmail);
                    
                    \Log::info('Welcome email sent after verification', ['user_id' => $user->id]);
                } catch (\Exception $e) {
                    \Log::error('Failed to send welcome email after verification: ' . $e->getMessage());
                    // Continue even if email fails
                }
                
                // Auto-login user after password reset/verification
                Auth::login($user);
                
                return redirect()->route('login')
                    ->with('success', 'Email verified successfully! Your account is now active. You can now log in.');
            } else {
                // Regular password reset - user already had verified email
                // Auto-login user after password reset
                Auth::login($user);
                
                return redirect()->route('login')
                    ->with('status', 'Password has been reset successfully! You are now logged in.');
            }
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}