@extends('layouts.public', ['title' => 'Browse Fields'])
@push('meta')
@endpush
@push('css')
    <link href="{{ theme_asset('css/search-mini.css') }}" rel="stylesheet">
@endpush



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        <div class="container w-full" >
        @isset($field)
            <div class="row">                
                <div class="col-md-12">
                     @include('partials.fancy_title', ['title' => 'SEARCH rESULTs', 'description' => 'We found ' . $resources->count() . ' resource(s) under ' . $field->title . ' for your search' ])
                </div>

                <div class="col-md-12">
                     @include('partials.search_mini', ['subfields' => $field->subfields])
                </div>

                <div class="clearfix mb-10"></div>


                <div class="col-md-9" >
                    @include('resource.partials.top_field_resource_grid', ['resources' => $resources])
                </div>

                 <div class="col-md-3">
                  {{-- @include('partials.sidebar') --}}
                </div>

            </div>
        @endisset
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

