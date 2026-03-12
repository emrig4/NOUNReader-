<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a href="/" class="logo">
                <img src="{{ asset('themes/airdgereaders/images/nounreader-logo-main.svg') }}" alt="readprojecttopics Logo" style="max-width: 200px;">
            </a>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        {{-- CASE 1: First time email verification - Show Login button --}}
        @if(isset($verified) && $verified && isset($isFirstVerification) && $isFirstVerification)
            <div class="mb-4 font-medium text-sm text-green-600">
                Your email has been verified successfully! You can now log in with your password.
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:border-red-700 focus:ring red-300 active:bg-red-600 transition">
                    {{ __('Login') }}
                </a>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Already have an account? Log in') }}
                </a>
            </div>

        {{-- CASE 2 & 3: Password Reset Form (For forgot password OR already verified) --}}
        @else
            @if(isset($verified) && $verified && isset($isFirstVerification) && !$isFirstVerification)
                <div class="mb-4 font-medium text-sm text-blue-600">
                    Reset your password. Enter your new password below.
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token ?? '' }}">
                {{-- Hidden field to indicate if this is a verification or password reset --}}
                @if(isset($verified) && $verified && isset($isFirstVerification) && $isFirstVerification)
                    <input type="hidden" name="is_verification" value="true">
                @endif

                <div class="block">
                    <x-jet-label for="email" value="{{ __('Email') }}" />
                    <x-jet-input id="email" 
                               class="block mt-1 w-full" 
                               type="email" 
                               name="email" 
                               value="{{ old('email', $email ?? '') }}" 
                               required autofocus />
                </div>

                <div class="mt-4">
                    <x-jet-label for="password" value="{{ __('New Password') }}" />
                    <x-jet-input id="password" 
                               class="block mt-1 w-full" 
                               type="password" 
                               name="password" 
                               required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-jet-label for="password_confirmation" value="{{ __('Confirm New Password') }}" />
                    <x-jet-input id="password_confirmation" 
                               class="block mt-1 w-full" 
                               type="password" 
                               name="password_confirmation" 
                               required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-jet-button>
                        {{ __('Set New Password') }}
                    </x-jet-button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Remember your password? Back to login') }}
                </a>
            </div>
        @endif
    </x-jet-authentication-card>
</x-guest-layout>