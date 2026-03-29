@extends('layouts.public', ['title' => 'Browse Fields'])

@push('meta')
    <meta name="description" content="Access organised NOUN study resources including course summaries, TMA, POP past questions, and exam preparation materials on Nounreader."/>
    
    <meta property="title" content="Nounreader – Study Resources and Academic Materials">
    
    <meta name="keywords" content="NOUN resources, TMA answers, POP past questions, NOUN summaries, study materials Nigeria, exam preparation, Nounreader">

    <meta property="og:title" content="Nounreader – Academic Resource Platform">
    <meta property="og:description" content="Access organised course summaries, TMA, POP, and exam preparation materials designed to help students study smarter.">
    <meta property="og:url" content="https://nounreader.com/">
@endpush

@push('css')
<style>
    .ereaders-main-section,
    .ereaders-main-section p,
    .ereaders-main-section li,
    .ereaders-main-section h2,
    .ereaders-main-section h4 {
        color: #000 !important;
    }

    .ereaders-main-section a {
        color: #28a745 !important;
    }

    .ereaders-main-section a:hover {
        color: #1e7e34 !important;
    }

    /* STEP DESIGN */
    .step-list {
        list-style: none;
        padding: 0;
    }

    .step-list li {
        background: #f8f9fa;
        padding: 12px 15px;
        margin-bottom: 10px;
        border-left: 4px solid #28a745;
        border-radius: 5px;
    }

    .step-number {
        font-weight: bold;
        color: #28a745;
        margin-right: 8px;
    }
    
    .ereaders-main-section h4 {
    color: #000 !important;
    font-weight: 700 !important;
    font-size: 18px;
    margin-top: 15px;
    margin-bottom: 10px;
    border-left: 4px solid #28a745;
    padding-left: 10px;
}
</style>
@endpush

@section('content')

<div class="ereaders-main-section ereaders-counterfull">
    <div class="container" style="width: 100%">

        <div class="row">

            <div class="col-md-12">
                <div class="ereaders-fancy-title">
                    <h2>Quick Guide</h2>
                    <div class="clearfix"></div>
                    <p>
                        Follow these simple steps to use Nounreader and access your study materials easily.
                    </p>
                </div>
            </div>

            <!-- LEFT -->
            <aside class="col-md-4">
                <div class="ereaders-faq-tabs">
                    <h2 class="ereaders-widget-title">CATEGORY</h2>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation">
                            <a href="#home" role="tab" data-toggle="tab">Students</a>
                        </li>
                        <li role="presentation">
                            <a href="#subscription" role="tab" data-toggle="tab">Credits</a>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- RIGHT -->
            <aside class="col-md-8">
                <div class="tab-content">

                    <!-- STUDENTS -->
                    <div role="tabpanel" class="tab-pane active" id="home">

                        <h4>HOW TO USE NOUNREADER</h4>

                        <ul class="step-list">
                            <li><span class="step-number">1.</span> <a href="/login">Login</a> or <a href="/register">Create an account</a></li>
                            <li><span class="step-number">2.</span> Search for your course (e.g., ACC419)</li>
                            <li><span class="step-number">3.</span> Select material type (TMA, POP, or Summary)</li>
                            <li><span class="step-number">4.</span> Click on any result to view details</li>
                            <li><span class="step-number">5.</span> Use your credits to unlock the material</li>
                            <li><span class="step-number">6.</span> Read online or download for study</li>
                        </ul>

                        <br>

                        <h4>HOW TO SEARCH QUICKLY</h4>

                        <ul class="step-list">
                            <li><span class="step-number">1.</span> Go to the search section</li>
                            <li><span class="step-number">2.</span> Enter your course code</li>
                            <li><span class="step-number">3.</span> Choose material type</li>
                            <li><span class="step-number">4.</span> Click search</li>
                            <li><span class="step-number">5.</span> Review results</li>
                            <li><span class="step-number">6.</span> Unlock with credits</li>
                        </ul>

                    </div>

                    <!-- CREDITS -->
                    <div role="tabpanel" class="tab-pane" id="subscription">

                        <h4>HOW TO BUY AND USE CREDITS</h4>

                        <ul class="step-list">
                            <li><span class="step-number">1.</span> Login to your account</li>
                            <li><span class="step-number">2.</span> Go to wallet or pricing page</li>
                            <li><span class="step-number">3.</span> Select a credit plan</li>
                            <li><span class="step-number">4.</span> Complete payment</li>
                            <li><span class="step-number">5.</span> Credits will be added instantly</li>
                            <li><span class="step-number">6.</span> Use credits to unlock materials</li>
                            <li><span class="step-number">7.</span> Top up anytime when finished</li>
                            <li><span class="step-number">8.</span> <a href="/faq">View FAQs for help</a></li>
                        </ul>

                    </div>

                </div>
            </aside>

        </div>

    </div>
</div>

@endsection