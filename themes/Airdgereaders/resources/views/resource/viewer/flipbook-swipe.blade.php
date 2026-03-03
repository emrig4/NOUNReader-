@extends('layouts.public', ['title' => 'Resource | ' . 'viewer' ])
@push('css')
  <link rel="stylesheet" type="text/css" href="{{ asset('vendor/swipebook/css/flipbook.style.css') }}">
  <style type="text/css">
      
    @media (min-width:1025px) { 
        /* big landscape tablets, laptops, and desktops */ 
        .flipbook-carousel-page{
        width: 3000px !important
        }
        .flipbook-carousel-page-bg {
            width: 3000px
        }
        .flipbook-carousel-page-inner{
            width: 3000px;
            height: 4000px;

        }
        .flipbook-carousel-page{
            width: 3000px
        }

        .flipbook-textLayer {
            width: 3000px
        }

        .flipbook-carousel-slide .slide-inner {
            width: 3000px !important;
            transform: translate(220.142px, 7.21919px) scale(0.302905) translateZ(0px);
            width: 1580.99px
        }

        .flipbook-carousel-slide {
            overflow: scroll;
        }*/
    }

    /*.flipbook-textLayer {
       z-index: -100
    }

    .preview-notice-wrapper{
       width: 50%;
        min-height: calc(100vh); 
        border: none;
        margin-top: 100px
    }

    .preview-notice {
        margin: auto;
    }

    .preview-notice h1{
        font-size: 50px
    }

    .preview-notice a {
       padding: 40px;
    }

    .flipbook-page-htmlContent {
        position: relative;
        top: 0;
        left: 0;
        transform-origin: 0 0;
    }

  </style>
@endpush

@section('breadcrumb')
@endsection

@php
  $file = file_get_contents('https://file-examples-com.github.io/uploads/2017/10/file-example_PDF_1MB.pdf');

  $base64_encode = base64_encode($file);

@endphp

@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section" id="app">
        <div class="col-md-12 h-screen">
            <vue-pdf-air base64="{!! $base64_encode !!}" pdfsrc="https://file-examples-com.github.io/uploads/2017/10/file-example_PDF_1MB.pdf"></vue-pdf-air>
            <div id="container" style="width:100%;height:100%"/>
        </div>

    </div>
    <!--// Main Section \\-->
@endsection


@push('js')
  <script src="{{ asset('vendor/swipebook/js/dev/flipbook.js') }}"></script>

    <script type="text/javascript">

      $(document).ready(function () {

          var pages = []
          pages[0] = {
              htmlContent:''
          }

          // set preview images for pages beyond the preview limit
          for (var i = 5; i <= 1000; i++) {
              // use image
              // pages[i] = {src:"../images/book2/page6.jpg", thumb:"../images/book2/thumb6.jpg", title:"Page six"}

              // use html ifrma content
              // pages[i] = {
              //     htmlContent: '<iframe src="preview-notice.html" style=" width: calc(100vh); min-height: calc(100vh); border: none;" ></iframe>'
              // }

              // use html ifrma content
              pages[i] = {
                  htmlContent: '<div class="preview-notice-wrapper" ><div class="preview-notice"><h1>You have reached the preview limit for this document</h1><h3>Please subsscribe to read/download complete document</h3><a href="/dsf">See Pricing</a></div></div>'
              }
          }
          
          $("#container").flipBook({
              // pages:[
              //     {src:"../images/book2/page1.jpg", thumb:"../images/book2/thumb1.jpg", title:"Cover"},
              //     {src:"../images/book2/page2.jpg", thumb:"../images/book2/thumb2.jpg", title:"Page two"},
              //     {src:"../images/book2/page3.jpg", thumb:"../images/book2/thumb3.jpg", title:"Page three"},
              //     {src:"../images/book2/page4.jpg", thumb:"../images/book2/thumb4.jpg", title:""},
              //     {src:"../images/book2/page5.jpg", thumb:"../images/book2/thumb5.jpg", title:"Page five"},
              //     {src:"../images/book2/page6.jpg", thumb:"../images/book2/thumb6.jpg", title:"Page six"},
              //     {src:"../images/book2/page7.jpg", thumb:"../images/book2/thumb7.jpg", title:"Page seven"},
              //     {src:"../images/book2/page8.jpg", thumb:"../images/book2/thumb8.jpg", title:"Last"}
              // ],

              pdfUrl:'https://file-examples-com.github.io/uploads/2017/10/file-example_PDF_1MB.pdf',
              viewMode:"swipe",
             singlePageMode:true,
              // zoomSize:1,
              pages:pages,
              layout: 1,
              // backgroundTransparent : true,
              lightBoxFullscreen  : true,
              // pageTextureSizeSmall: 3000,
          });
      })
  </script>


@endpush

