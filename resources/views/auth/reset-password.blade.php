<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a href="/" class="logo">
                <img src="{{ asset('themes/airdgereaders/images/nounreader-logo-main.svg') }}" alt="Nounreader Logo">
            </a>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- FIX: Use request() helper for token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <!-- FIX: Use request() for email value consistency -->
                <x-jet-input id="email" 
                           class="block mt-1 w-full" 
                           type="email" 
                           name="email" 
                           value="{{ old('email', request('email')) }}" 
                           required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" 
                           class="block mt-1 w-full" 
                           type="password" 
                           name="password" 
                           required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" 
                           class="block mt-1 w-full" 
                           type="password" 
                           name="password_confirmation" 
                           required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Reset Password') }}
                </x-jet-button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-gray-900">
                {{ __('Remember your password? Back to login') }}
            </a>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>