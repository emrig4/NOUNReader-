<x-guest-layout>

<x-jet-authentication-card>

<x-slot name="logo">
<img src="{{ asset('themes/airdgereaders/images/nounreader-logo-main.svg') }}">
</x-slot>

{{-- LOGIN ERROR MESSAGE --}}
@if(session('error'))

<div style="background:#f8d7da;color:#842029;padding:10px;margin-bottom:15px;border-radius:4px;">
{{ session('error') }}
</div>
@endif

{{-- SUCCESS MESSAGE --}}
@if(session('success'))

<div style="background:#d1e7dd;color:#0f5132;padding:10px;margin-bottom:15px;border-radius:4px;">
{{ session('success') }}
</div>
@endif

{{-- VALIDATION ERRORS --}}
@if ($errors->any())

<div style="background:#f8d7da;color:#842029;padding:10px;margin-bottom:15px;border-radius:4px;">
{{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('login') }}">
@csrf

<div>
<label>Email</label>
<input 
type="email" 
name="email" 
value="{{ old('email') }}" 
required 
autofocus 
class="block mt-1 w-full">
</div>

<div class="mt-4">
<label>Password</label>
<input 
type="password" 
name="password" 
required 
class="block mt-1 w-full">
</div>

{{-- FORGOT PASSWORD --}}

<div style="margin-top:8px">
<a href="https://pamdev.online/forgot-password" 
style="font-size:13px;color:#2563eb;text-decoration:underline;">
Forgot your password?
</a>
</div>

<div class="mt-4">
<label>
<input type="checkbox" name="remember">
Remember me
</label>
</div>

<div class="mt-4">
<button type="submit" style="padding:8px 20px;background:#2563eb;color:white;border-radius:4px;">
Log in
</button>
</div>

<div style="margin-top:10px">
<span>Don't have an account?</span>
<a href="{{ route('register') }}">Signup Here</a>
</div>

</form>

</x-jet-authentication-card>

</x-guest-layout>
