@extends('layouts.auth')
@push('css')
    <link href="{{ asset('themes/airdgereaders/css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="login-container ">
        <div class="loginbox bg-white">
            <div class="loginbox-title">SIGN IN</div>
            <div class="loginbox-social">
                <div class="social-title ">Connect with Your Social Accounts</div>
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

            <form method="post"  action="{{ route('login') }}">
                @csrf()
                <div class="loginbox-textbox">
                    <x-jet-validation-errors class="mb-4" />
                    <input type="text" name="email" class="form-control" placeholder="Email">
                </div>
                <div class="loginbox-textbox">
                    <input type="password" name="password" class="form-control " placeholder="Password">
                </div>
                <div class="loginbox-forgot">
                    <a href="">Forgot Password?</a>
                </div>
                <div class="loginbox-submit">
                    <input type="submit" class="btn btn-primary btn-block" value="Login">
                </div>
            </form>
            <div class="loginbox-signup">
                <a href="{{ route('register') }}">Sign Up With Email</a>
            </div>
        </div>
        <div class="logobox">
            <img src="{{ asset('themes/airdgereaders/images/logo.png') }}">
        </div>
    </div>
@endsection
