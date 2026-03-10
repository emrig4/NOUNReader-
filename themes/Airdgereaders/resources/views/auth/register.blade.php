@extends('layouts.auth')

@push('css')

<link href="{{ asset('themes/airdgereaders/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="login-container animated fadeInDown bootstrap snippets bootdeys">
    <div class="loginbox bg-white">
        <div class="loginbox-title">REGISTER</div>

```
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

        <!-- Honeypot fields -->
        <div style="position:absolute; left:-9999px;">
            <input type="text" name="middle_name" tabindex="-1" autocomplete="off">
            <input type="email" name="website" tabindex="-1" autocomplete="off">
        </div>

        <div class="loginbox-textbox">
            <x-jet-validation-errors class="mb-4" />
        </div>

        <!-- Last Name -->
        <div class="loginbox-textbox">
            <input type="text"
                   class="form-control @error('last_name') is-invalid @enderror"
                   name="last_name"
                   placeholder="Last Name"
                   maxlength="50"
                   required>
        </div>

        <!-- First Name -->
        <div class="loginbox-textbox">
            <input type="text"
                   class="form-control @error('first_name') is-invalid @enderror"
                   name="first_name"
                   placeholder="First Name"
                   maxlength="50"
                   required>
        </div>

        <!-- Email -->
        <div class="loginbox-textbox">
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email"
                   placeholder="Email"
                   maxlength="255"
                   required>
        </div>

        <!-- Password -->
        <div class="loginbox-textbox">
            <input type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password"
                   placeholder="Password"
                   minlength="8"
                   maxlength="72"
                   required>
            <small class="text-muted">Minimum 8 characters</small>
        </div>

        <!-- Confirm Password -->
        <div class="loginbox-textbox">
            <input type="password"
                   class="form-control"
                   name="password_confirmation"
                   placeholder="Confirm Password"
                   minlength="8"
                   maxlength="72"
                   required>
        </div>

        <!-- Google reCAPTCHA -->
        <div class="loginbox-textbox" style="margin-top:15px;">
           <div class="g-recaptcha" data-sitekey="6LeCjnssAAAAAGcGs2IOuQuVQDJ8z3t3bLlteCdG"></div>
        </div>

        <!-- Terms -->
        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="loginbox-textbox">
            <label>
                <input type="checkbox" name="terms" required>
                I agree to the
                <a target="_blank" href="{{ route('terms.show') }}">Terms</a>
                and
                <a target="_blank" href="{{ route('policy.show') }}">Privacy Policy</a>
            </label>
        </div>
        @endif

        <!-- Submit -->
        <div class="loginbox-submit">
            <button type="submit" class="btn btn-primary btn-block">
                Register
            </button>
        </div>
    </form>

    <div class="loginbox-signup">
        <p>Already have an account?
            <a href="/login">Login Here</a>
        </p>
    </div>
</div>

<div class="logobox">
    <img src="{{ asset('themes/airdgereaders/images/nounreader-logo-main.svg') }}">
</div>
```

</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

@endsection
