<?php

namespace App\Actions\Fortify;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Laravel\Fortify\Contracts\CreatesNewUsers;

use Laravel\Jetstream\Jetstream;

use App\Modules\Account\Models\Account;

use App\Modules\Wallet\Models\SubscriptionWallet;

use App\Modules\Wallet\Models\CreditWallet;

use App\Services\AutoCreditService;

use App\Services\EmailNotificationService;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class CreateNewUser implements CreatesNewUsers

{

    use PasswordValidationRules;

    /**

     * Validate and create a newly registered user.

     *

     * @param  array  $input

     * @return \App\Models\User

     */

    public function create(array $input)

    {

        // =====================================================

        // SPAM PROTECTION LAYER 1: Honeypot Validation

        // =====================================================

        // Check honeypot fields - if filled, this is a bot

        if (!empty($input['middle_name']) || !empty($input['website'])) {

            Log::warning('Spam registration attempt - honeypot triggered', [

                'ip' => request()->ip(),

                'email' => $input['email'] ?? 'unknown',

                'user_agent' => request()->userAgent(),

                'timestamp' => now()

            ]);

            // Create a fake user to confuse the bot

            $fakeUser = User::create([

                'last_name' => 'Bot',

                'first_name' => 'Spam',

                'email' => 'spam_' . time() . '@example.com',

                'password' => Hash::make(\Str::random(32)),

                'has_set_permanent_password' => true,

            ]);

            // Return fake success

            return $fakeUser;

        }

        // =====================================================

        // SPAM PROTECTION LAYER 2: Field Validation

        // =====================================================

        Validator::make($input, [

            'last_name' => [

                'required', 

                'string', 

                'max:50',

                'regex:/^[A-Za-z\s\-]+$/', // Letters, spaces, hyphens only

            ],

            'first_name' => [

                'required', 

                'string', 

                'max:50',

                'regex:/^[A-Za-z\s\-]+$/', // Letters, spaces, hyphens only

            ],

            'email' => [

                'required', 

                'string', 

                'email:rfc,dns',

                'max:255', 

                'unique:users'

            ],

            'password' => $this->passwordRules(),

            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',

            // =====================================================

            // SPAM PROTECTION LAYER 3: reCAPTCHA Validation

            // =====================================================

            'g-recaptcha-response' => ['required', function ($attribute, $value, $fail) {

                // Check if reCAPTCHA is configured

                if (!config('recaptcha.secret')) {

                    // If reCAPTCHA is not configured, allow registration but log warning

                    Log::warning('reCAPTCHA not configured - skipping validation');

                    return;

                }

                $secret = config('recaptcha.secret');

                $response = $value;

                $userIP = request()->ip();

                // Verify with Google reCAPTCHA API

                $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

                $ch = curl_init($verifyUrl);

                curl_setopt_array($ch, [

                    CURLOPT_POST => true,

                    CURLOPT_POSTFIELDS => http_build_query([

                        'secret' => $secret,

                        'response' => $response,

                        'remoteip' => $userIP,

                    ]),

                    CURLOPT_RETURNTRANSFER => true,

                    CURLOPT_TIMEOUT => 10,

                ]);

                $verifyResponse = curl_exec($ch);

                curl_close($ch);

                $responseData = json_decode($verifyResponse, true);

                // Check if verification was successful

                if (!$responseData || !isset($responseData['success']) || !$responseData['success']) {

                    $errorCodes = $responseData['error-codes'] ?? [];

                    Log::warning('reCAPTCHA verification failed', [

                        'error_codes' => $errorCodes,

                        'ip' => $userIP,

                        'email' => $input['email'] ?? 'unknown'

                    ]);

                    $fail('Security verification failed. Please try again.');

                }

                // For reCAPTCHA v3, check the score

                if (isset($responseData['score']) && 

                    $responseData['score'] < 0.5 && 

                    config('recaptcha.version') == 3) {

                    Log::warning('reCAPTCHA low score detected', [

                        'score' => $responseData['score'],

                        'ip' => $userIP,

                        'email' => $input['email'] ?? 'unknown'

                    ]);

                    $fail('Security verification failed. Please try again.');

                }

            }],

        ], [

            'last_name.regex' => 'Last name can only contain letters, spaces, and hyphens.',

            'first_name.regex' => 'First name can only contain letters, spaces, and hyphens.',

            'email.email' => 'Please enter a valid email address.',

            'email.unique' => 'This email is already registered.',

            'terms.required' => 'You must accept the Terms of Service and Privacy Policy.',

            'g-recaptcha-response.required' => 'Please complete the security verification.',

        ])->validate();

        Log::info('CreateNewUser: Starting user registration', ['email' => $input['email']]);

        // =====================================================

        // SANITIZE INPUT DATA

        // =====================================================

        $firstName = ucwords(strtolower(trim($input['first_name'])));

        $lastName = ucwords(strtolower(trim($input['last_name'])));

        $email = strtolower(trim($input['email']));

        // Create user

        $user = User::create([

            'last_name' => $lastName,

            'first_name' => $firstName,

            'email' => $email,

            'password' => Hash::make($input['password']),

            'has_set_permanent_password' => true,

        ]);

        Log::info('CreateNewUser: User created', ['user_id' => $user->id]);

        // Create account

        Account::create([

            'user_id' => $user->id,

            'status' => 1,

            'created_at' => now(),

            'updated_at' => now(),

        ]);

        Log::info('CreateNewUser: Account created', ['user_id' => $user->id]);

        // Create wallets

        SubscriptionWallet::create(['user_id' => $user->id]);

        CreditWallet::create(['user_id' => $user->id]);

        Log::info('CreateNewUser: Wallets created', ['user_id' => $user->id]);

        // Grant auto credit to first-time users

        $autoCreditService = new AutoCreditService();

        $creditGranted = $autoCreditService->grantAutoCredit($user);

        Log::info('CreateNewUser: Auto credit process completed', [

            'user_id' => $user->id,

            'credit_granted' => $creditGranted

        ]);

        // Create Paystack customer

        $user->createOrGetPaystackCustomer(['email' => $user->email]);

        Log::info('CreateNewUser: Paystack customer created', ['user_id' => $user->id]);

        // Log user in directly

        Auth::login($user);

        // Send welcome email with credit notification

        try {

            $emailService = new EmailNotificationService();

            $creditAmount = $autoCreditService->getAutoCreditAmount();

            $emailService->sendWelcomeVerifiedUserWithCreditEmail($user->email, [

                'user' => $user,

                'credit_amount' => $creditAmount

            ]);

            Log::info('CreateNewUser: Welcome email sent', [

                'user_id' => $user->id,

                'email' => $user->email

            ]);

        } catch (\Exception $e) {

            Log::error('CreateNewUser: Failed to send welcome email', [

                'user_id' => $user->id,

                'error' => $e->getMessage()

            ]);

        }

        return $user;

    }

}