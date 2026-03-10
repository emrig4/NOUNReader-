<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a href="/" class="logo">
                <img src="{{ asset('themes/airdgereaders/images/rpt2.svg') }}" alt="">
            </a>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></asset-path>
                </svg>
            </div>
            
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Check Your Email</h2>
            
            <p class="text-gray-600 mb-6">
                We've sent a verification link to:<br>
                <span class="font-semibold text-gray-900">{{ $email }}</span>
            </p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-blue-800">
                    <strong>Next Steps:</strong>
                </p>
                <ul class="text-sm text-blue-700 mt-2 list-disc list-inside">
                    <li>Check your email inbox for the verification link</li>
                    <li>Click the link to verify your email address</li>
                    <li>After verification, you can log in to your account</li>
                </ul>
            </div>
            
            <p class="text-sm text-gray-500 mb-6">
                Didn't receive the email? Check your spam folder or 
                <form method="POST" action="{{ route('verification.resend') }}" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="text-blue-600 hover:text-blue-800 underline">
                        click here to resend
                    </button>
                </form>
            </p>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                Back to Login
            </a>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>