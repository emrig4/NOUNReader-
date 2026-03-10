<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Jetstream;
use App\Modules\Account\Models\Account;
use App\Modules\Wallet\Models\SubscriptionWallet;
use App\Modules\Wallet\Models\CreditWallet;
use App\Services\AutoCreditService;
use App\Services\EmailNotificationService;

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
     * Handle registration - With Email Verification
     * Note: Accounts and Wallets are automatically created by User model's booted() event
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
        ])->validate();

        Log::info('AuthController: Starting user registration with email verification', ['email' => $request->email]);

        try {
            DB::beginTransaction();

            // Create user with email NOT verified
            // Note: Account, SubscriptionWallet, and CreditWallet are automatically 
            // created by the User model's booted() event
            $user = User::create([
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'has_set_permanent_password' => true,
                'email_verified_at' => null, // NOT VERIFIED - require email verification
                'status' => 'pending',
            ]);

            Log::info('AuthController: User created with pending verification', ['user_id' => $user->id]);
            Log::info('AuthController: Account and Wallets auto-created by User model event', ['user_id' => $user->id]);

            // Grant auto credit to first-time users
            $autoCreditService = new AutoCreditService();
            $creditGranted = $autoCreditService->grantAutoCredit($user);

            Log::info('AuthController: Auto credit process completed', [
                'user_id' => $user->id,
                'credit_granted' => $creditGranted
            ]);

            // Create Paystack customer
            $user->createOrGetPaystackCustomer(['email' => $user->email]);

            // Send verification link using password reset service
            try {
                // Create reset token for verification
                $token = app('auth.password.broker')->createToken($user);
                $actionUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
                
                // Send verification email using the password reset mail template
                Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($user, $actionUrl, true));
                
                Log::info('AuthController: Verification email sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('AuthController: Failed to send verification email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail registration if email fails
            }

            DB::commit();

            Log::info('AuthController: User registered successfully, verification pending', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Log user out and redirect to check email notification page
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect to check email notification page
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
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login - With Email Verification Check
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (!$user) {
            return back()->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // Check if user has set permanent password
        if (!$user->has_set_permanent_password) {
            return back()->withInput()
                ->with('error', 'Please set your permanent password first.');
        }

        // CHECK IF EMAIL IS VERIFIED - New verification flow
        if (is_null($user->email_verified_at)) {
            // User hasn't verified their email
            return back()->withInput()
                ->with('error', 'Your email address has not been verified. Please check your email for the verification link or <a href="' . route('verification.resend') . '?email=' . urlencode($user->email) . '" class="underline">click here to resend</a>.');
        }

        // Attempt authentication
        if (!Hash::check($request->password, $user->password)) {
            return back()->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // Login successful
        Auth::login($user);
        
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        // Redirect to account page after successful login
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

    /**
     * Show email verification notice page
     */
    public function showVerificationNotice(Request $request)
    {
        $email = $request->session()->get('email') ?? $request->get('email');
        
        if (!$email) {
            return redirect()->route('register')
                ->with('error', 'Please register first.');
        }

        return view('auth.verification-notice', [
            'email' => $email
        ]);
    }

    /**
     * Resend verification link
     */
    public function resendVerificationLink(Request $request)
    {
        $email = $request->session()->get('email') ?? $request->get('email');
        
        if (!$email) {
            return back()->with('error', 'Invalid request. Please register first.');
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Check if already verified
        if (!is_null($user->email_verified_at)) {
            return redirect()->route('login')
                ->with('info', 'Your email is already verified. Please log in.');
        }

        try {
            // Create reset token for verification
            $token = app('auth.password.broker')->createToken($user);
            $actionUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);
            
            // Send verification email
            Mail::to($user->email)->send(new \App\Mail\PasswordResetMail($user, $actionUrl, true));
            
            Log::info('Verification link resent', ['user_id' => $user->id, 'email' => $user->email]);
            
            return back()->with('success', 'Verification link sent! Please check your email.');
            
        } catch (\Exception $e) {
            Log::error('Failed to resend verification link', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to send verification link. Please try again.');
        }
    }
}