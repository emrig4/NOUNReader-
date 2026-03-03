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
        @isset($type)
            <div class="row">  

                <div class="col-md-12">
                    @include('partials.fancy_title', [
                        'title' => $type->title,
                        'description' => 'Find the right resource for your research'
                    ])
                </div>


                <!-- recent pubs -->
                <div class="col-md-12">
                    @include('resource.partials.top_field_resource_grid', ['resources' => $resources])
                </div>

                 <div class="col-md-12">
                    <div class="w-1/6 mx-auto">
                        {!!  $resources->render() !!}
                    </div>
                </div>

            </div>
        @endisset
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

