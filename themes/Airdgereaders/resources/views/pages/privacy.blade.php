@extends('layouts.public', ['title' => 'Privacy Policy'])

@push('meta')
    <meta name="description" content="Nounreader Privacy Policy – Learn how we collect, use, and protect your personal information while using our platform."/>
    <meta property="title" content="Nounreader – Privacy Policy">
    <meta name="keywords" content="NOUN privacy policy, Nounreader data protection, user data policy, Nigeria student platform privacy">
    <meta property="og:title" content="Nounreader – Privacy Policy">
    <meta property="og:description" content="Understand how Nounreader collects, uses, and protects your personal information.">
@endpush

@push('css')
@endpush

@section('content')

<div class="ereaders-main-section ereaders-counterfull">
    <div class="container" style="width: 100%">

        <div class="row">

            <div class="col-md-12 text-center">
                <img src="{{ theme_asset('extra-images/privacy.png') }}" alt="Privacy Policy" class="mx-auto">
            </div>

            <div class="col-md-3"></div>

            <div class="col-md-8">
                <div class="ereaders-about-us">

                    <h4><span class="ereaders-color">Personal Information</span></h4>
                    <p>
                        At Nounreader, your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your information when you use our platform.
                    </p>
                    <p>
                        This policy applies only to online activities and covers information shared or collected on Nounreader. It does not apply to offline data collection or third-party platforms.
                    </p>

                    <h4><span class="ereaders-color">Consent</span></h4>
                    <p>
                        By using our website (<a href="https://nounreader.com/">https://nounreader.com/</a>), you agree to this Privacy Policy.
                    </p>

                    <h4><span class="ereaders-color">Information We Collect</span></h4>
                    <p>
                        We may collect personal information such as your name, email address, and account details when you register or contact us.
                    </p>
                    <p>
                        Additional information may include messages you send, support requests, and usage data to improve your experience.
                    </p>

                    <h4><span class="ereaders-color">How We Use Your Information</span></h4>
                    <p>We use your information to:</p>
                    <ul>
                        <li>Provide and maintain our platform</li>
                        <li>Improve user experience and functionality</li>
                        <li>Process payments and manage credits</li>
                        <li>Respond to support requests</li>
                        <li>Send updates and notifications</li>
                        <li>Prevent fraud and ensure security</li>
                    </ul>

                    <h4><span class="ereaders-color">Log Files</span></h4>
                    <p>
                        Like most websites, Nounreader uses log files to track visits. This may include IP address, browser type, ISP, date/time, and pages visited. This data is not linked to personal identity.
                    </p>

                    <h4><span class="ereaders-color">Cookies</span></h4>
                    <p>
                        We use cookies to improve your experience by remembering preferences and enhancing performance. You can disable cookies in your browser settings.
                    </p>

                    <h4><span class="ereaders-color">Third-Party Services</span></h4>
                    <p>
                        We may use third-party services such as payment processors and analytics tools. These services may collect data according to their own privacy policies.
                    </p>

                    <h4><span class="ereaders-color">Your Rights</span></h4>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access your personal data</li>
                        <li>Request correction or deletion</li>
                        <li>Restrict or object to data processing</li>
                    </ul>
                    <p>
                        To exercise these rights, please contact us.
                    </p>

                    <h4><span class="ereaders-color">Children's Information</span></h4>
                    <p>
                        Nounreader does not knowingly collect personal information from children under 13. If such data is found, we will remove it promptly.
                    </p>

                </div>
            </div>

            <div class="col-md-2"></div>

        </div>
    </div>
</div>

@endsection