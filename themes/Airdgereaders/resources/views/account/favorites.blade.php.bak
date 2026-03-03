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
                    <p class="text-bold">{{$favorites->count() }} Favorites</p>
                    <div class="mt-3 clearfix"></div>
                </div>  
            </div>
            

            <div class=" ereaders-blog-grid">
                <ul class="row">
                    @foreach($favorites as $favorite)
                        <li style="list-style: none;" class="col-md-4">
                            @include('resource.partials.inc.grid_card', ['resource' => $favorite->resource])
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="flex justify-center flex-1 sm:hidden">
                {!! $favorites->render() !!}
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

