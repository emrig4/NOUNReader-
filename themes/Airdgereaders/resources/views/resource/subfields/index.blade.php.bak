@extends('layouts.public', ['title' => 'Browse Topics'])
@push('meta')
@endpush
@push('css')
@endpush



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        <div class="container" style="width: 100%">
            <div class="row">                
                <div class="col-md-12">
                     @include('partials.fancy_title', ['title' => 'Browse Topics', 'description' => 'We have over 50,0000 materials across various subjects/topics'])
                </div>


                <div class="col-md-12">
                    @include('resource.partials.subfields_grid', ['subfields' => $subfields])
                </div>
                <!--  <div class="col-md-3">
                    include('partials.sidebar')
                </div> -->

                <!-- recent pubs -->
                <div class="col-md-12">
                     @include('partials.fancy_heading', ['title' => 'Recent Works', 'description' => 'View recent publications'])
                     @include('resource.partials.recent_resource_grid')
                </div>

            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

