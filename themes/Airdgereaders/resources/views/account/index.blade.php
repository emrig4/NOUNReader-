@extends('layouts.account', ['title' => 'My Account'])
@push('css')
    <link href="{{ asset('themes/airdgereaders/css/stats.css') }}" rel="stylesheet">
@endpush



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">

        <!-- dashboard nav -->
        @include('partials.usermenu')

        <div class="container">
            <div class="row">                
    
                <!-- <div class="col-md-12">

                    <div class="ereaders-book-detail">
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
                    </div>
                </div> -->

                <div class="col-md-12">
                    @include('partials.userstats',  ['resources' => $resources])
                </div>

                <div class="col-md-12">
                     @include('resource.partials.resource_grid', ['resource' => $resources])
                </div>



            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

@push('js')
@endpush

