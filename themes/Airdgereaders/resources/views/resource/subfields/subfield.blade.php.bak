@extends('layouts.public', ['title' => 'Browse Topics'])
@push('meta')
@endpush
@push('css')
     @livewireStyles
@endpush



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        <div class="container" >
            <div class="row">                
                
                <div class="col-md-12">
                    @include('partials.fancy_title', ['title' => $subfield->title, 'description' => 'We found ' .$subfield->resourceCount() . ' resource(s) under this topic'])

                    @livewire('resource::resources.topic-resource')
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

@push('js')
    @livewireScripts
@endpush
