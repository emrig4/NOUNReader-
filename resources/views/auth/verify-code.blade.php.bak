<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Verification Code</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .countdown-timer {
            animation: pulse 1.5s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .resend-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Verify Your Email
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Enter the 6-digit verification code sent to your email
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Message -->
            @if (session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.verify') }}">
                @csrf

                <input type="hidden" name="email" value="{{ old('email', $email ?? session('email')) }}">

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            value="{{ old('email', $email ?? session('email')) }}" 
                            readonly 
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500 sm:text-sm"
                        >
                    </div>
                </div>

                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">
                        6-Digit Verification Code
                    </label>
                    <div class="mt-1">
                        <input 
                            id="code" 
                            name="code" 
                            type="text" 
                            value="{{ old('code') }}" 
                            maxlength="6" 
                            pattern="[0-9]{6}"
                            placeholder="123456"
                            required 
                            autofocus
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-center text-lg tracking-widest"
                            style="letter-spacing: 0.5em;"
                        >
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Enter the 6-digit code from your email
                    </p>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('register') }}" 
                           class="text-sm text-indigo-600 hover:text-indigo-500">
                            Back to Register
                        </a>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Verify Code
                    </button>
                </div>
            </form>

            <!-- Resend Section -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-3">
                        Didn't receive the code?
                    </p>
                    
                    @if($resendAvailable ?? false)
                        <form method="POST" action="{{ route('verification.resend') }}" id="resend-form">
                            @csrf
                            <button type="submit" 
                                    id="resend-button"
                                    class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <span id="resend-text">Resend Verification Code</span>
                            </button>
                        </form>
                    @else
                        <div class="w-full">
                            <button type="button" 
                                    id="resend-button"
                                    disabled
                                    class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-400 bg-gray-100 cursor-not-allowed">
                                <span id="resend-text" class="countdown-timer">
                                    @if(isset($resendTimer) && $resendTimer > 0)
                                        Resend in {{ $resendTimer }}s
                                    @else
                                        Resend available in 60s
                                    @endif
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900">
                    Already have an account? Sign in
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-format verification code input
        document.getElementById('code').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length > 6) value = value.substring(0, 6); // Limit to 6 digits
            e.target.value = value;
        });

        // Auto-focus next input if needed
        document.getElementById('code').addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                // Auto-submit form when 6 digits are entered (optional)
                // setTimeout(() => e.target.closest('form').submit(), 500);
            }
        });

        // Countdown timer for resend button
        const resendButton = document.getElementById('resend-button');
        const resendText = document.getElementById('resend-text');
        
        @if(!($resendAvailable ?? true))
            let countdown = {{ $resendTimer ?? 60 }};
            
            if (countdown > 0) {
                const timer = setInterval(function() {
                    countdown--;
                    resendText.textContent = `Resend in ${countdown}s`;
                    
                    if (countdown <= 0) {
                        clearInterval(timer);
                        // Enable the resend button
                        resendButton.disabled = false;
                        resendButton.className = resendButton.className.replace('text-gray-400 bg-gray-100 cursor-not-allowed', 'text-gray-700 bg-white hover:bg-gray-50 focus:ring-indigo-500');
                        resendButton.className = resendButton.className.replace('border-gray-300', 'border-indigo-500');
                        resendText.textContent = 'Resend Verification Code';
                        
                        // Update the form to be functional
                        const resendForm = document.getElementById('resend-form');
                        if (resendForm) {
                            resendForm.innerHTML = `
                                @csrf
                                <button type="submit" 
                                        class="w-full flex justify-center py-2 px-4 border border-indigo-500 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    ${resendText.textContent}
                                </button>
                            `;
                        }
                    }
                }, 1000);
            }
        @endif

        // Handle resend form submission with loading state
        const resendForm = document.getElementById('resend-form');
        if (resendForm) {
            resendForm.addEventListener('submit', function(e) {
                const button = resendForm.querySelector('button');
                const originalText = button.innerHTML;
                
                button.innerHTML = 'Sending...';
                button.disabled = true;
                
                // Re-enable after 3 seconds (in case of error)
                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.disabled = false;
                }, 3000);
            });
        }
    </script>
</body>
</html>
