@extends('layouts.auth')
@push('css')
<link href="{{ asset('themes/airdgereaders/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="login-container animated fadeInDown bootstrap snippets bootdeys">
    <div class="loginbox bg-white">
        <div class="loginbox-title">SIGN IN</div>

        <div class="loginbox-social">
            <div class="social-title">Connect with Your Social Accounts</div>
            <div class="social-buttons">
                <a href="" class="button-facebook">
                    <i class="social-icon fa fa-facebook"></i>
                </a>
                <a href="" class="button-twitter">
                    <i class="social-icon fa fa-twitter"></i>
                </a>
                <a href="" class="button-google">
                    <i class="social-icon fa fa-google-plus"></i>
                </a>
            </div>
        </div>

        <div class="loginbox-or">
            <div class="or-line"></div>
            <div class="or">OR</div>
        </div>

        <form method="post" action="{{ route('register') }}" class="register-form" id="register-form">
            @csrf
            
            <!-- SPAM PROTECTION: Hidden honeypot fields -->
            <div class="honeypot-fields" style="position: absolute; left: -9999px; opacity: 0;">
                <input type="text" name="middle_name" tabindex="-1" autocomplete="off" placeholder="">
                <input type="email" name="website" tabindex="-1" autocomplete="off" placeholder="">
            </div>

            <!-- Display Session Errors -->
            @if(session('error'))
            <div class="alert alert-danger" role="alert" style="margin-bottom: 15px;">
                <strong>Error:</strong> {{ session('error') }}
            </div>
            @endif

            <!-- Display Validation Errors -->
            @if($errors->any())
            <div class="alert alert-danger" role="alert" style="margin-bottom: 15px;">
                <strong>Please correct the following errors:</strong>
                <ul style="margin-bottom: 0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="loginbox-textbox">
                <x-jet-validation-errors class="mb-4" />
            </div>

            <!-- Last Name Field -->
            <div class="loginbox-textbox">
                <input 
                    type="text" 
                    class="form-control @error('last_name') is-invalid @enderror" 
                    required 
                    name="last_name" 
                    placeholder="Last Name"
                    maxlength="50"
                    autocomplete="family-name"
                >
                @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- First Name Field -->
            <div class="loginbox-textbox">
                <input 
                    type="text" 
                    class="form-control @error('first_name') is-invalid @enderror" 
                    required 
                    name="first_name" 
                    placeholder="First Name"
                    maxlength="50"
                    autocomplete="given-name"
                >
                @error('first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="loginbox-textbox">
                <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    required 
                    name="email" 
                    placeholder="Email"
                    maxlength="255"
                    autocomplete="email"
                >
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="loginbox-textbox">
                <input 
                    type="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    required 
                    name="password" 
                    placeholder="Password"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                >
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <small class="text-muted">Min 8 characters</small>
            </div>

            <!-- Confirm Password Field -->
            <div class="loginbox-textbox">
                <input 
                    type="password" 
                    class="form-control" 
                    required 
                    name="password_confirmation" 
                    placeholder="Confirm Password"
                    minlength="8"
                    maxlength="72"
                    autocomplete="new-password"
                >
            </div>

            <!-- User Info Message -->
            <div class="loginbox-textbox">
                <div class="alert alert-info">
                    <small><i class="fas fa-info-circle"></i> <strong>Info:</strong> Choose a secure password. You will receive 190 free credit units to read and download project materials.</small>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="loginbox-submit">
                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex">
                            <x-jet-checkbox required name="terms" id="terms"/>
                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="loginbox-submit">
                <input type="submit" class="btn btn-primary btn-block" value="Register">
            </div>
        </form>

        <div class="loginbox-signup">
            <p class="text-sm">Already have an account? <span><a href="/login" class="text-sm">Login Here</a></span></p>
        </div>
    </div>

    <div class="logobox">
        <img src="{{ asset('themes/airdgereaders/images/projectandmaterials.logo.png') }}">
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for error or success messages and show alert
    @if(session('error'))
        alert('{{ session('error') }}');
    @endif
    
    @if(session('success'))
        alert('{{ session('success') }}');
    @endif
});
</script>
@endsection