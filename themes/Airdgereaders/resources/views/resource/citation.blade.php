@extends('layouts.public', ['title' => 'Resource | ' . $resource->title ])
@push('meta')
    <meta name="description" content="Publish your research works on the largest academia library, gain recognigtion and also earn through our premium program."/>
    <meta property="title" content="projectandmaterials.com, Digital Academic Resources, Research, Project, Thesis">
    <meta name="keywords" content="readprojecttopics, Digital Academic Resources, Research, Project, Thesis">
    <meta property="og:title" content="projectandmaterials.com, Digital Academic Resources, Research, Project, Thesis">
    <meta property="og:description" content="Publish your research works on the largest academia library, gain recognigtion and also earn through our premium program.">
@endpush
@push('css')
    <style>
        .cite {
            border: thin solid whitesmoke;
            padding: 3%;
            font-size: 20px;
            background: whitesmoke;
            box-shadow: inset 6px 6px 20px #dcdcdc;
        }
    </style>
@endpush


@section('breadcrumb')
   
    {{ Breadcrumbs::render('resource', $resource) }}

@endsection

@section('content')
    <!--// Main Sections \\-->
    <div class="ereaders-main-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ereaders-book-wrap">
                        <div class="row " id="app">

                            <div id="copyalert" class="hidden">
                                <p class="alert alert-success">Copied to clipboard</p>
                            </div>

                             <!-- file preview -->
                            <div  id="style-4-scrollbar" class="col-md-12" style="height: 500px; overflow-y: scroll;">

                                <h2>Resource citation in MLA 7 format*</h2>
                                <div class="cite" id="mla7">
                                    @foreach($authors as $author)
                                        <span>{{ $author }}, </span>
                                    @endforeach
                                    <span>{{ $resource->title }}</span>. 
                                    <span>{{ $resource->publisher_city }}</span>: 
                                    <span>{{ $resource->publisher }}</span>, 
                                    <span>{{ $resource->publication_year }}</span>. projectandmaterials.com. 
                                    <span>{{ now()->format('D, d, m, Y') }}</span>
                                </div>
                                <div class="mb-10">
                                    <button class="btn btn-primary float-right" onclick="copy('mla7')">Copy</button>
                                </div>

                                
                                <h2>Resource citation in APA 6 format*</h2>
                                <div class="cite" id="mla7">
                                    <span>{{ $ap6_authors }}</span>
                                    <span>({{ $resource->publication_year }})</span>
                                    <span>{{ $resource->title }}</span>.
                                    <span>{{ $resource->publisher_city }}</span>:
                                    <span>{{ $resource->publisher }}</span>,
                                    <span>Retreived from {{ 'https://www.projectandmaterials.com' }}</span>
                                </div>
                                <div class="mb-10"><button class="btn btn-primary float-right" onclick="copy('mla7')">Copy</button></div>


                                <h2>Resource citation in APA 7 format*</h2>
                                <div class="cite" id="mla7">
                                    <span>{{ $ap7_authors }}</span>
                                    <span>({{ $resource->publication_year }})</span>
                                    <span>{{ $resource->title }}</span>.
                                    <span>{{ $resource->publisher }}.</span>,
                                    <span>{{ 'https://www.projectandmaterials.com' }}</span>
                                </div>
                                <div class="mb-10"><button class="btn btn-primary float-right" onclick="copy('mla7')">Copy</button></div>


                                <h2>Resource citation in MLA 8 format*</h2>
                                <div class="cite" id="mla7">
                                    <span>{{ $mla8_authors }}</span>
                                    <span>{{ $resource->title }}</span>.
                                    <span>{{ $resource->publisher }},</span>,
                                    <span>({{ $resource->publication_year }}).</span>
                                    <span>{{ 'https://www.projectandmaterials.com' }}</span>
                                </div>
                                <div class="mb-10"><button class="btn btn-primary float-right" onclick="copy('mla7')">Copy</button></div>

                            </div>
                                   
                        </div>
                    </div>

                    @include('resource.partials.resource_related', ['related' => $resource->related()] )
                </div>
            </div>

            <div class="row">
                <div class="col-md-12"></div>
            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection


@push('js')
    <script>
        function copy(element_id){
            var aux = document.createElement("div");

            aux.setAttribute("contentEditable", true);
            aux.innerHTML = document.getElementById(element_id).innerHTML;
            aux.setAttribute("onfocus", "document.execCommand('selectAll',false,null)"); 
          

            document.body.prepend(aux);
            aux.focus();
            document.execCommand("copy");
            document.body.removeChild(aux);

            $('#copyalert').removeClass('hidden')
            setTimeout(function(){
                $('#copyalert').addClass('hidden')
            }, 3000)
            
        }
    </script>
@endpush


