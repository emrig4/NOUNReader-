@extends('layouts.account')
@push('css')
    <link href="{{ asset('themes/airdgereaders/css/stats.css') }}" rel="stylesheet">
@endpush

@php
@endphp



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        
        <!-- dashboard nav -->
        @include('partials.usermenu')

        <div class="container" style="width: 100%">
            <div class="row">
                 <div class="col-md-12">
        
        <div class="ereaders-shop-detail">
            
            <div class="flex justify-center relative -mt-24" id="alert-holder">
                <div class="alert text-center bg-white my-5  border border-gray-300">
                    <img class="mx-auto h-10" src="{{ theme_asset('images/ranc.jpg') }}">
                    <p class="text-bold">{{$followings->count() }} Followings</p>
                    <div class="mt-3 clearfix"></div>
                </div>  
            </div>
            

            <div class=" ereaders-blog-grid">
                <ul class="row">
                    @foreach($followings as $following)
                        <div class="ereaders-admin-post">
                            <figure> <a href="{{ route('account.profile',  $following->followee->username ) }}"><img src="{{ $following->followee->profile_photo_url }}"></a></figure>
                            <div class="ereaders-admin-text">
                                <h5><a href="{{ route('account.profile',  $following->followee->username ) }}">{{ $following->follower->name }}</a></h5>
                                <ul class="ereaders-admin-social">
                                    <li><a href="{{ $following->followee->account->facebook }}" class="fa fa-facebook"></a></li>
                                    <li><a href="{{ $following->followee->account->twitter }}" class="fa fa-twitter"></a></li>
                                </ul>
                                <span>Author</span>
                                <p> {!! mb_strimwidth($following->followee->account->biography, 0, 160, "...") !!}</p>
                            </div>
                        </div>
                    @endforeach
                </ul>
            </div>

            <div class="flex justify-center flex-1 sm:hidden">
                {!! $followings->render() !!}
            </div>

        </div>
    </div>
            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

@push('js')
@endpush

