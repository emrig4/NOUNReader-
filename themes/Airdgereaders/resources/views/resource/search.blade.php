@extends('layouts.public', ['title' => 'Browse Fields'])
@push('meta')
    <meta name="description" content="Publish your research works on the largest academia library, gain recognigtion and also earn through our premium program."/>
    <meta property="title" content="projectandmaterials.com, Digital Academic Resources, Research, Project, Thesis">
    <meta name="keywords" content="readprojecttopics, Digital Academic Resources, Research, Project, Thesis">
    <meta property="og:title" content="projectandmaterials.com, Digital Academic Resources, Research, Project, Thesis">
    <meta property="og:description" content="Publish your research works on the largest academia library, gain recognigtion and also earn through our premium program.">
@endpush
@push('css')
    <link href="{{ theme_asset('css/search-mini.css') }}" rel="stylesheet">
    @livewireStyles
@endpush



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        <div class="container w-full" >
        @isset($resources)
            <div class="row">                
                
                <div class="col-md-12" >
                    @livewire('resource::resources.search-result')
                </div>

            </div>
        @endisset
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

@push('js')
    @livewireScripts
@endpush

