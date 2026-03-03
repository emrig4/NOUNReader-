@extends('layouts.account')
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

                <div class="col-md-12">
                    @include('partials.userstats')
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

