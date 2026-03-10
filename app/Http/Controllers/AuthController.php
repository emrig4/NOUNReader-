<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;
use App\Modules\Account\Models\Account;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Models\CreditWallet;
use App\Services\AutoCreditService;
use App\Services\EmailNotificationService;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use App\Services\ReferralService;

class AuthController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration
     * 1. Create user with password
     * 2. Send verification email
     * 3. Redirect to "Check Your Email" page
     */
    public function register(Request $request)
    {
        // Validate input
        Validator::make($request->all(), [
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ], [
            'email.unique' => 'An account with this email already exists. Please login or use the forgot password option.',
        ])->validate();

        Log::info('AuthController: Starting user registration', ['email' => $request->email]);

        try {
            // Get referral code from URL or session
$referralCode = $request->ref ?? session('referral_code');

DB::beginTransaction();

            // Create user with UNVERIFIED email
            $user = User::create([
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'has_set_permanent_password' => true,
                'email_verified_at' => null, // NOT verified - requires email verification
            ]);
            // ✅ PROCESS REFERRAL
if ($referralCode) {
    try {
        $referralService = new ReferralService();
        $referralService->processReferral($user, $referralCode);

        Log::info('Referral processed', [
            'user_id' => $user->id,
            'referral_code' => $referralCode
        ]);
    } catch (\Exception $e) {
        Log::error('Referral processing failed', [
            'error' => $e->getMessage()
        ]);
    }
}

            Log::info('AuthController: User created with unverified email', ['user_id' => $user->id]);
            Log::info('AuthController: Account and Wallets auto-created', ['user_id' => $user->id]);

            // Grant auto credit
            $autoCreditService = new AutoCreditService();
            $creditGranted = $autoCreditService->grantAutoCredit($user);

            Log::info('AuthController: Auto credit process completed', [
                'user_id' => $user->id,
                'credit_granted' => $creditGranted
            ]);

            // Create Paystack customer
            $user->createOrGetPaystackCustomer(['email' => $user->email]);

            DB::commit();

            // ✅ SEND VERIFICATION EMAIL
            try {
                $token = app('auth.password.broker')->createToken($user);
                $verificationUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
                
                Mail::to($user->email)->send(new PasswordResetMail($user, $verificationUrl));
                Log::info('AuthController: Verification email sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('AuthController: Failed to send verification email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }

            Log::info('AuthController: User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // ✅ REDIRECT TO "CHECK YOUR EMAIL" PAGE
            return redirect()->route('verification.notice')
                ->with('email', $user->email);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('AuthController: Registration failed', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return back()->withInput()
                ->with('error', 'Registration failed. Please try again.');
        }
    }

    /**
     * Show "Check Your Email" page (after registration)
     */
    public function showVerificationNotice()
    {
        $email = session('email');
        
        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Please register to receive a verification email.');
        }

        return view('auth.verification-notice', ['email' => $email]);
    }

    /**
     * Resend verification email
     */
    public function resendVerification(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No account found with this email address.');
        }

        if ($user->email_verified_at) {
            return redirect()->route('login')
                ->with('info', 'Your email is already verified. Please log in.');
        }

        // Generate new verification token
        try {
            $token = app('auth.password.broker')->createToken($user);
            $verificationUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
            
            Mail::to($user->email)->send(new PasswordResetMail($user, $verificationUrl));
            Log::info('AuthController: Verification email resent', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('AuthController: Failed to resend verification email', [
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to resend verification email. Please try again.');
        }

        return back()->with('success', 'Verification email sent! Please check your inbox.');
    }

    /**
     * ✅ SIMPLE VERIFICATION - Click link → Go to LOGIN (no password reset)
     */
    public function verifyEmail(Request $request)
    {
        $token = $request->token;
        $email = $request->email;

        if (!$token || !$email) {
            return redirect()->route('login')
                ->with('error', 'Invalid verification link.');
        }

        // Find user by email
        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::warning('AuthController: User not found', ['email' => $email]);
            return redirect()->route('login')
                ->with('error', 'Invalid verification link.');
        }

        // Verify the password reset token
        $passwordBroker = app('auth.password.broker');
        $isValidToken = $passwordBroker->tokenExists($user, $token);

        if (!$isValidToken) {
            Log::warning('AuthController: Invalid token', ['email' => $email]);
            return redirect()->route('login')
                ->with('error', 'Invalid or expired verification link.');
        }

        // Check if already verified
        if ($user->email_verified_at) {
            return redirect()->route('login')
                ->with('info', 'Your email is already verified. Please log in.');
        }

        // ✅ MARK EMAIL AS VERIFIED
        $user->email_verified_at = now();
        $user->save();

        // Delete the used token
        $passwordBroker->deleteToken($user);

        Log::info('AuthController: Email verified successfully', ['user_id' => $user->id, 'email' => $email]);

        // ✅ REDIRECT DIRECTLY TO LOGIN (no password reset step!)
        return redirect()->route('login')
            ->with('success', 'Email verified! You can now login with your password.');
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // ✅ CHECK IF EMAIL IS VERIFIED
        if (!$user->email_verified_at) {
            return back()->withInput()
                ->with('error', 'Please check your email to verify your account first.');
        }

        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return back()->withInput()
                ->with('error', 'Wrong password. Please try again or use forgot password.');
        }

        // Login successful
        Auth::login($user);
        
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        return redirect('/account')->with('success', 'Welcome back!');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('User logged out', [
            'user_id' => $user ? $user->id : null,
            'email' => $user ? $user->email : null
        ]);

        return redirect()->route('login')
            ->with('success', 'Logged out successfully.');
    }
}