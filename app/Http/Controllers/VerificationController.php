<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Services\VerificationService;
use App\Services\AutoCreditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use App\Mail\WelcomeVerifiedUserWithCreditMail;

class VerificationController extends Controller
{
    protected $verificationService;

    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

  public function register(Request $request)
{
    // =====================================================
    // SPAM PROTECTION - Add at the start of method
    // =====================================================
    $spamService = new \App\Services\SpamProtectionService();
    $spamCheck = $spamService->isSpam($request);
    
    if ($spamCheck['blocked']) {
        return back()
            ->withInput($request->only('first_name', 'last_name', 'email'))
            ->with('error', $spamCheck['reason']);
    }
    // =====================================================

    // Your existing validation (keep exactly as is)
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:8|confirmed',
    ]);

    try {
        // Your existing code (keep exactly as is)
        $activeUser = User::where('email', $request->email)
            ->where('status', 'active')
            ->first();
        
        if ($activeUser) {
            return back()
                ->withInput($request->only('first_name', 'last_name', 'email'))
                ->with('error', 'An account with this email already exists. Please log in instead.');
        }

        // ... rest of your existing registration code ...
        
        // Create user directly (your existing logic)
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'has_set_permanent_password' => true,
            'status' => 'active',
        ]);

        // Auto-login
        Auth::login($user);
        
        // Grant credits
        try {
            $autoCreditService = new AutoCreditService();
            $autoCreditService->grantAutoCredit($user);
        } catch (\Exception $e) {
            Log::error('Auto credit error: ' . $e->getMessage());
        }

        return redirect()->route('account.subscription')
            ->with('success', 'Registration successful! Welcome!');

    } catch (\Exception $e) {
        Log::error('Registration failed: ' . $e->getMessage());
        return back()
            ->withInput($request->only('first_name', 'last_name', 'email'))
            ->with('error', 'Registration failed. Please try again.');
    }
}
    public function showVerifyForm()
    {
        $email = session('email');
        
        if (!$email) {
            return redirect()
                ->route('register')
                ->with('error', 'Please register first to receive your verification code.');
        }

        // Get user to check if they need verification
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            // Create new user record for registration
            $registrationData = session('registration_data');
            if ($registrationData) {
                $user = User::create([
                    'first_name' => $registrationData['first_name'],
                    'last_name' => $registrationData['last_name'],
                    'email' => $registrationData['email'],
                    'email_verified_at' => null, // Will be set after code verification
                    'has_set_permanent_password' => false,
                    'status' => 'pending', // ✅ ADDED: Set status to pending
                ]);
            }
        }

        // ✅ FIXED: Pass email to view to prevent "Undefined variable" error
        return view('auth.verify-code', [
            'email' => $email,
            'resendAvailable' => $this->isResendAvailable($email)
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $email = session('email');
        
        if (!$email) {
            return redirect()
                ->route('register')
                ->with('error', 'Invalid session. Please register again.');
        }

        try {
            $user = $this->verificationService->verifyCodeAndGetUser($email, $request->code);
            
            if (!$user) {
                return back()
                    ->withInput()
                    ->with('error', 'Invalid or expired verification code. Please try again or resend a new code.');
            }

            // Mark email as verified and update status
            $user->email_verified_at = now();
            $user->status = 'active'; // ✅ ADDED: Set status to active
            $user->has_set_permanent_password = false; // Will be set when they set password
            $user->save();
            
            // Auto-login the user after successful verification
            Auth::login($user);
            
            Log::info('User verified and logged in', ['user_id' => $user->id, 'email' => $email]);

            return redirect()
                ->route('verification.set-password')
                ->with('success', 'Email verified successfully! Please set your permanent password to complete your registration.');
                
        } catch (\Exception $e) {
            Log::error('Verification failed', ['error' => $e->getMessage(), 'email' => $email]);
            
            return back()
                ->withInput()
                ->with('error', 'Verification failed. Please try again or resend a new code.');
        }
    }

    public function resendCode(Request $request)
    {
        $email = session('email');
        
        if (!$email) {
            return redirect()
                ->route('register')
                ->with('error', 'Invalid session. Please register again.');
        }

        try {
            // Check rate limiting (max 3 resends per hour)
            $rateLimitKey = "verification_resend_{$email}";
            if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);
                return back()
                    ->with('error', "Too many resend attempts. Please wait {$seconds} seconds before requesting another code.");
            }

            // Send new verification code
            $this->sendVerificationCode($email);
            
            // Rate limit the resend attempt
            RateLimiter::hit($rateLimitKey, 3600); // 1 hour window
            
            Log::info('Verification code resent', ['email' => $email]);
            
            return back()
                ->with('success', 'New verification code sent! Please check your email.');
                
        } catch (\Exception $e) {
            Log::error('Failed to resend verification code', ['error' => $e->getMessage(), 'email' => $email]);
            
            return back()
                ->with('error', 'Failed to resend code. Please try again.');
        }
    }

    public function showSetPasswordForm()
    {
        if (!Auth::check()) {
            return redirect()
                ->route('register')
                ->with('error', 'Please verify your email first.');
        }

        $user = Auth::user();
        
        if ($user->hasSetPermanentPassword()) {
            return redirect()
                ->route('login')
                ->with('info', 'You have already set your permanent password. Please log in.');
        }

        return view('auth.set-password');
    }

    public function setPassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()
                ->route('register')
                ->with('error', 'Please verify your email first.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = Auth::user();
            
            // Update user password and mark as permanent password set
            $user->password = Hash::make($request->password);
            $user->has_set_permanent_password = true;
            $user->status = 'active'; // ✅ ENSURE status is active
            $user->save();
            
            Log::info('User set permanent password', ['user_id' => $user->id, 'email' => $user->email]);

            // Auto credit integration - Grant credits to first-time users
            $this->grantAutoCreditToUser($user);
try {
    $welcomeEmail = new WelcomeVerifiedUserWithCreditMail(
        $user,
        190.00,                    // Auto credit amount
        $user->wallet_balance,     // Current wallet balance
        now()                      // Verification date
    );
    
    Mail::to($user->email)->send($welcomeEmail);
    
    Log::info("Welcome email sent to {$user->email} with auto credits");
    
} catch (\Exception $e) {
    Log::error("Failed to send welcome email to {$user->email}: " . $e->getMessage());
    // Continue processing even if email fails
}
            // Logout user after password setup
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('success', 'Password set successfully! Welcome! You have been credited with auto credits to read complete project material. You can now log in with your email and password.');
                
        } catch (\Exception $e) {
            Log::error('Failed to set password', ['error' => $e->getMessage(), 'user_id' => Auth::id()]);
            
            return back()
                ->with('error', 'Failed to set password. Please try again.');
        }
    }

    /**
     * Send verification code for any email (for various scenarios)
     */
    public function sendCodeForEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            $email = $request->email;
            
            // Rate limiting for send code requests
            $rateLimitKey = "verification_send_{$email}";
            if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
                $seconds = RateLimiter::availableIn($rateLimitKey);
                return response()->json([
                    'success' => false,
                    'message' => "Too many requests. Please wait {$seconds} seconds."
                ]);
            }

            $this->sendVerificationCode($email);
            RateLimiter::hit($rateLimitKey, 3600);

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send verification code', ['error' => $e->getMessage(), 'email' => $request->email]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please try again.'
            ]);
        }
    }

    /**
     * Request verification code via AJAX
     */
    public function requestCode(Request $request)
    {
        $email = session('email') ?? $request->get('email');
        
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'No email provided for verification.'
            ]);
        }

        // Check if resend is available
        if (!$this->isResendAvailable($email)) {
            $nextAvailable = $this->getNextResendTime($email);
            return response()->json([
                'success' => false,
                'message' => "Please wait before requesting another code. Next available at: {$nextAvailable}"
            ]);
        }

        // Send new code
        try {
            $this->sendVerificationCode($email);
            
            Log::info('Verification code resent via AJAX', ['email' => $email]);
            
            return response()->json([
                'success' => true,
                'message' => 'New verification code sent! Please check your email.',
                'nextResendAvailable' => $this->getNextResendTime($email)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to resend verification code via AJAX', ['error' => $e->getMessage(), 'email' => $email]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code. Please try again.'
            ]);
        }
    }

    /**
     * Check if resend is available for an email
     */
    protected function isResendAvailable($email)
    {
        $rateLimitKey = "verification_resend_{$email}";
        return !RateLimiter::tooManyAttempts($rateLimitKey, 3);
    }

    /**
     * Get next available resend time for an email
     */
    protected function getNextResendTime($email)
    {
        $rateLimitKey = "verification_resend_{$email}";
        $seconds = RateLimiter::availableIn($rateLimitKey);
        
        if ($seconds > 0) {
            return now()->addSeconds($seconds)->format('H:i:s');
        }
        
        return 'now';
    }

    /**
     * Send verification code
     */
    protected function sendVerificationCode($email)
    {
        $code = $this->verificationService->generateAndSendCode($email);
        
        // Store current code in session for verification
        session(['verification_code' => $code]);
        
        Log::info('Verification code sent', ['email' => $email, 'code' => $code]);
    }

    /**
     * Grant auto credit to a verified user
     */
    protected function grantAutoCreditToUser(User $user)
    {
        try {
            Log::info('AutoCredit: Starting auto credit process for verified user', [
                'user_id' => $user->id,
                'user_email' => $user->email
            ]);

            $autoCreditService = new AutoCreditService();
            $result = $autoCreditService->grantAutoCredit($user);
            
            if ($result) {
                Log::info('AutoCredit: Successfully granted auto credit to verified user', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
            } else {
                Log::warning('AutoCredit: Failed to grant auto credit to verified user', [
                    'user_id' => $user->id,
                    'user_email' => $user->email
                ]);
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('AutoCredit: Exception occurred while granting auto credit', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'error_message' => $e->getMessage()
            ]);
            return false;
        }
    }

    public function enhancedLogin(Request $request)
    {
        // Validate login credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        
        // Check if user exists
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withInput()->with('error', 'Invalid login credentials.');
        }
        
        // If user hasn't set permanent password, redirect to verification
        if (!$user->hasSetPermanentPassword()) {
            session(['email' => $request->email]);
            return redirect()->route('verification.show-verify-form')
                ->with('message', 'Please verify your email first before logging in.');
        }
        
        // User has permanent password - attempt authentication
        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/account/subscription');
        }
        
        return back()->withInput()->with('error', 'Invalid login credentials.');
    }
}