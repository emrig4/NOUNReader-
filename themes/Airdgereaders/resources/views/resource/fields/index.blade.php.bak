@extends('layouts.public', ['title' => 'Browse Fields'])
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
                     @include('partials.fancy_title', ['title' => 'Browse Fields', 'description' => 'Find the right resource for your research'])
                </div>


                <div class="col-md-12">
                    @include('resource.partials.fields_grid')
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


    <div class="hidden">
        <h1>Resources</h1>
        <h3>Resources</h3>
        <img src="" alt="Resources">
    </div>
@endsection

