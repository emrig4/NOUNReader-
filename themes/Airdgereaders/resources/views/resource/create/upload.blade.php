@extends('layouts.public', ['title' => 'Upload'])
@push('css')
@endpush



@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        <div class="container">
            <div class="row"> 
                <!-- title -->
                <div class="col-md-12">
                    @include('partials.fancy_title', ['title' => 'Upload File', 'description' => 'Click on browse to upload file you want to publish, we only accept either docx or pdf'])
                </div>

                <div class="col-md-12">
                  @include('partials.flash')
                </div>

                <div class="col-md-12 ereaders-contact-wrap flex justify-center" >
                    <div class="ereaders-contact-form" >
                        <form class="uploadform" enctype="multipart/form-data" method="post" action="{{route('files.store.upload')}}">
                            @csrf()
                            <ul class="">
                                <li class="">
                                    <input required=""  type="file" onchange="parseWordDocxFile(this)" name="file">
                                </li>
                                <li class="">
                                    <input type="submit" class="" name="submit" value="Upload Now">
                                    <span class="output_message"></span>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal-content" id="preview-container">

            <!-- modal -->
            <div class="row">
                <!-- search Modal: modalPoll -->
                <div class="modal fade right" id="modalPoll-1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                  aria-hidden="true" data-backdrop="false">
                  <div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
                    
                    </div>
                  </div>
                </div>
                <!-- Modal: modalPoll -->
            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

@push('js')

    <!--Docs-->
    <script src="{{asset('vendor/officedocs/docx/mammoth.browser.min.js')}}"></script>

    <script type="text/javascript">
         // <input type="file" onchange="parseWordDocxFile(this)">
        const  previewContainer = document.getElementById('preview-container')
        function parseWordDocxFile(inputElement) {
            var files = inputElement.files || [];
            if (!files.length) return;
            var file = files[0];

            console.time();
            var reader = new FileReader();
            reader.onloadend = function(event) {
              var arrayBuffer = reader.result;
              // debugger

              mammoth.convertToHtml({arrayBuffer: arrayBuffer}).then(function (resultObject) {
                previewContainer.innerHTML = resultObject.value
                console.log(resultObject.value)
              })
              console.timeEnd();

              // mammoth.extractRawText({arrayBuffer: arrayBuffer}).then(function (resultObject) {
              //   // result2.innerHTML = resultObject.value
              //   console.log(resultObject.value)
              // })

              // mammoth.convertToMarkdown({arrayBuffer: arrayBuffer}).then(function (resultObject) {
              //   // result3.innerHTML = resultObject.value
              //   console.log(resultObject.value)
              // })
            };
            reader.readAsArrayBuffer(file);
        }

    </script>
@endpush

