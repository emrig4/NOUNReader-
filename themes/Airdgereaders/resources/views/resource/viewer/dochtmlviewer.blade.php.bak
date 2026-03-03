@extends('layouts.reader', ['title' => 'Resource | ' . $resource->title ])
@push('css')

<script type="text/javascript" src="{{ asset('/vendor/officedocs/jquery/jquery-1.12.4.min.js') }}"></script>

<!--PDF--> 
<link rel="stylesheet" href="{{ asset('/vendor/officedocs/pdf/pdf.viewer.css') }}"> 
<script src="{{ asset('vendor/officedocs/pdf/pdf.js') }}"></script> 

<!--Docs-->
<script src="{{ asset('/vendor/officedocs/docx/jszip-utils.js') }}"></script>
<script src="{{asset('vendor/officedocs/docx/mammoth.browser.min.js')}}"></script>

<!--PPTX-->
<link rel="stylesheet" href="{{ asset('/vendor/officedocs/PPTXjs/css/pptxjs.css') }}">
<link rel="stylesheet" href="{{asset('/vendor/officedocs/PPTXjs/css/nv.d3.min.css')}}">

<!-- optional if you want to use revealjs (v1.11.0) -->
<link rel="stylesheet" href="{{ asset('/vendor/officedocs/revealjs/reveal.css') }}">
<script type="text/javascript" src="{{ asset('/vendor/officedocs/PPTXjs/js/filereader.js') }}"></script>
<script type="text/javascript" src="{{ asset('/vendor/officedocs/PPTXjs/js/d3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/vendor/officedocs/PPTXjs/js/nv.d3.min.js') }}"></script>
<script type="text/javascript" src=" {{ asset('/vendor/officedocs/PPTXjs/js/pptxjs.js') }} "></script>
<script type="text/javascript" src="{{ asset('/vendor/officedocs/PPTXjs/js/divs2slides.js') }}"></script>

<!--All Spreadsheet -->
<link rel="stylesheet" href="{{ asset('/vendor/officedocs/SheetJS/handsontable.full.min.css') }}">
<script type="text/javascript" src="{{ asset('/vendor/officedocs/SheetJS/handsontable.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/vendor/officedocs/SheetJS/xlsx.full.min.js') }}"></script>

<!--Image viewer--> 
<link rel="stylesheet" href="{{ asset('/vendor/officedocs/verySimpleImageViewer/css/jquery.verySimpleImageViewer.css') }}">
<script type="text/javascript" src="{{ asset('/vendor/officedocs/verySimpleImageViewer/js/jquery.verySimpleImageViewer.js') }}"></script>

<!--officeToHtml-->
<script  src="{{ asset('/vendor/officedocs/officeToHtml/officeToHtml.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/vendor/officedocs/officeToHtml/officeToHtml.css') }}">
@endpush

@section('breadcrumb')
@endsection



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section" id="app">
        <div class="col-md-12" style="height: 1200px">
         <!--  <button class="btn btn-primary m-2" onclick="openFullscreen()">Open in Fullscreen</button> -->
         <div id="resolte-contaniner"></div>

            
        </div>
    </div>
    <!--// Main Section \\-->
@endsection


@push('js')
   
    <script src="{{ mix('/js/app.js') }}"></script>

    <!-- full screen function -->
    <script defer="">
      // var elem = document.getElementById("viewerContainer");
      // function openFullscreen() {
      //   if (elem.requestFullscreen) {
      //     elem.requestFullscreen();
      //   } else if (elem.mozRequestFullScreen) { /* Firefox */
      //     elem.mozRequestFullScreen();
      //   } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
      //     elem.webkitRequestFullscreen();
      //   } else if (elem.msRequestFullscreen) { /* IE/Edge */
      //     elem.msRequestFullscreen();
      //   }
      // }
      console.log($)

       var file_path = "https://file-examples-com.github.io/uploads/2017/02/file-sample_500kB.docx"; 
          $("#resolte-contaniner").officeToHtml({
             url: file_path
      });


      
    </script>



 


@endpush

