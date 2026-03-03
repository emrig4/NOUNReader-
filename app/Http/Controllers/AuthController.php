<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
     * Handle registration - 2022 STYLE (No Verification, Logout After)
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

        Log::info('AuthController: Starting user registration (2022 style)', ['email' => $request->email]);

        try {
            DB::beginTransaction();

            // Create user - 2022 STYLE: password is permanent immediately
            // Note: Account, SubscriptionWallet, and CreditWallet are automatically 
            // created by the User model's booted() event
            $user = User::create([
                'last_name' => $request->last_name,
                'first_name' => $request->first_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'has_set_permanent_password' => true,
            ]);

            Log::info('AuthController: User created', ['user_id' => $user->id]);
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

            // 2022 STYLE: Send welcome email with credit notification
            try {
                $emailService = new EmailNotificationService();
                $creditAmount = $autoCreditService->getAutoCreditAmount();

                $emailService->sendWelcomeVerifiedUserWithCreditEmail($user->email, [
                    'user' => $user,
                    'credit_amount' => $creditAmount
                ]);

                Log::info('AuthController: Welcome email sent', ['user_id' => $user->id]);
            } catch (\Exception $e) {
                Log::error('AuthController: Failed to send welcome email', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail registration if email fails
            }

            DB::commit();

            Log::info('AuthController: User registered successfully (2022 style)', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // 2022 STYLE: Log user out and redirect to login page
            // This ensures auto credit is properly saved before user accesses dashboard
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect to login page with success message
            return redirect()->route('login')
                ->with('success', 'Account created successfully! Your free credits have been added. Please login to continue.');

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
     * Handle login - 2022 STYLE (No Verification, Redirect to Dashboard)
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

        // 2022 STYLE: No verification pending check - direct login
        // Just check if user has permanent password set
        if (!$user->has_set_permanent_password) {
            return back()->withInput()
                ->with('error', 'Please set your permanent password first.');
        }

        // Attempt authentication
        if (!Hash::check($request->password, $user->password)) {
            return back()->withInput()
                ->with('error', 'Invalid email or password.');
        }

        // Login successful - 2022 STYLE
        Auth::login($user);
        
        Log::info('User logged in successfully (2022 style)', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        // 2022 STYLE: Redirect to /dashboard after successful login
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