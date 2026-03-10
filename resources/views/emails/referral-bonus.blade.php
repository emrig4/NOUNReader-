@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif;">
    <div style="background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        
        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ asset('themes/airdgereaders/images/projectandmaterials.logo.png') }}" alt="{{ config('app.name') }}" style="max-height: 60px;">
        </div>

        <!-- Header -->
        <h1 style="color: #23A455; text-align: center; margin-bottom: 20px;">
            🎉 Referral Bonus Received!
        </h1>

        <!-- Content -->
        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            Hi {{ $referrer->first_name }},
        </p>

        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            Great news! Someone registered using your referral link, and you've received a bonus!
        </p>

        <!-- Bonus Amount -->
        <div style="background: #f0fdf4; border: 2px solid #23A455; border-radius: 10px; padding: 20px; text-align: center; margin: 30px 0;">
            <h2 style="color: #23A455; font-size: 36px; margin: 0;">
                +{{ number_format($bonusAmount, 0) }} Credits
            </h2>
            <p style="color: #666; margin-top: 10px;">
                Added to your account
            </p>
        </div>

        <p style="color: #333; font-size: 16px; line-height: 1.6;">
            Keep sharing your referral link to earn more credits!
        </p>

        <!-- CTA Button -->
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/account') }}" style="background: #23A455; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">
                View Your Account
            </a>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #999; font-size: 14px;">
            <p>Thank you for being a part of {{ config('app.name') }}!</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</div>
@endsection