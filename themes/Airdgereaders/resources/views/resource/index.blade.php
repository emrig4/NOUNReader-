@extends('layouts.user')
@push('meta')
<title>Browse Project Topics & Materials | Project Topics & Materials</title>
<meta name="description" content="Browse our extensive collection of project topics, materials, thesis, and research works. Find the perfect resource for your academic project.">
<meta name="keywords" content="browse project topics, project materials library, thesis, dissertation, academic resources, research works">

{{-- Open Graph Meta Tags --}}
<meta property="og:title" content="Browse Project Topics & Materials | Project Topics & Materials">
<meta property="og:description" content="Browse our extensive collection of project topics, materials, thesis, and research works.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ route('resources.index') }}">
<meta property="og:image" content="{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}">

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Browse Project Topics & Materials">
<meta name="twitter:description" content="Browse our extensive collection of project topics, materials, thesis, and research works.">
<meta name="twitter:image" content="{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ route('resources.index') }}">
@endpush
@push('css')
@endpush



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        <div class="container">
            <div class="row">                
    
                <div class="col-md-12">
                    <!-- <div class="ereaders-book-detail">
                        <ul>
                            <li>
                                <h6>Dashboard</h6>
                                <p>Cover Book MockUp By ebook Pro</p>
                            </li>
                            <li>
                                <h6>Upload</h6>
                                <p>Murial Barbery</p>
                            </li>
                            <li>
                                <h6>My Works</h6>
                                <p>Business And Accounts</p>
                            </li>
                            <li>
                                <h6>My Subscription</h6>
                                <p>December 13, 2017</p>
                            </li>
                            <li>
                                <h6>Orders</h6>
                                <p>Management And Technology</p>
                            </li>
                            <li>
                                <h6>Payouts</h6>
                                <p>12 Chapters And 550 Pages</p>
                            </li>
                            <li>
                                <h6>Settings</h6>
                                <p>12 Chapters And 550 Pages</p>
                            </li>
                            <li>
                                <h6>Saved Works</h6>
                                <p>12 Chapters And 550 Pages</p>
                            </li>
                            <li>
                                <h6>All Works</h6>
                                <p>12 Chapters And 550 Pages</p>
                            </li>
                        </ul>
                    </div> -->
                </div>

            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

