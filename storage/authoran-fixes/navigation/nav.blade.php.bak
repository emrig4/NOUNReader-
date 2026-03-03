@extends('layouts.app')
@section('content')
<style>
.logout-link {
    background: none;
    border: none;
    color: inherit;
    cursor: pointer;
    text-decoration: none;
    padding: 0;
    font-size: inherit;
}
.logout-link:hover {
    color: #007cba;
    text-decoration: none;
}
</style>
<div class="ereaders-main-content">
    <div class="ereaders-main-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ereaders-main-header-inner">
                        <div class="ereaders-main-header-left">
                            <a href="{{ route('home') }}" class="logo"><img src="{{ asset('themes/airdgereaders/images/logo.png') }}" alt=""></a>
                        </div>
                        <div class="ereaders-main-header-right">
                            <nav class="ereaders-mainnavigation">
                                <a href="#menu" class="menu-link"><i class="icon-bars"></i></a>
                                <div class="navigation">
                                    <ul>
                                        <li class="active"><a href="{{ route('home') }}">Home</a></li>
                                        <li><a href="{{ route('resources.index') }}">Resources</a></li>
                                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                                        <li><a href="{{ route('about') }}">About</a></li>
                                        <li><a href="{{ route('contact') }}">Contact</a></li>
                                    </ul>
                                </div>
                            </nav>
                            @auth
                                <div class="ereaders-user-option">
                                    <div class="user-name">
                                        <span>{{ Auth::user()->first_name }} <i class="fa fa-angle-down"></i></span>
                                        <ul class="ereaders-user-dashboard">
                                            <li><a href="{{ route('account.dashboard') }}">Dashboard</a></li>
                                            <li><a href="{{ route('account.profile') }}">Profile</a></li>
                                            <li>
                                                <form action="{{ url('/logout') }}" method="POST" style="margin: 0;">
                                                    @csrf
                                                    <button type="submit" class="logout-link">Logout</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            @else
                                <div class="ereaders-user-login">
                                    <a href="{{ url('/login') }}">Login</a>
                                    <a href="{{ url('/register') }}" class="ereaders-btn ereaders-btn2">Register</a>
                                </div>
                            @endauth
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
