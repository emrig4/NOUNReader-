@component('mail::message')
# Verify Your Email Address

Hello {{ $user->name ?? $user->first_name . ' ' . $user->last_name ?? 'User' }},

Thank you for registering with us! Please verify your email address by clicking the button below.

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

If you're having trouble with the button above, copy and paste the URL below into your web browser:

{{ $verificationUrl }}

This link will expire in 24 hours. If you didn't create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }} Team

@slot('footer')
@component('mail::subcopy')
If you're having trouble with the button above, copy and paste the URL below into your web browser:
{{ $verificationUrl }}
@endcomponent
@endslot
@endcomponent
