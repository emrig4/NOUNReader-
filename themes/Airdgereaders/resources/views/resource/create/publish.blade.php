@extends('layouts.account', ['title' => 'Publish'])
@push('css')
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link href="{{ asset('themes/airdgereaders/css/publish.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/airdgereaders/css/tag.css') }}" rel="stylesheet">

@endpush

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/29.1.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
    <script>
       ClassicEditor
        .create( document.querySelector( '#overview' ) )
        .then( editor => {
        } )
        .catch( error => {
                console.error( error );
        } );

        $(document).ready(function () {
            $('.selectize').selectize({
              sortField: 'text'
            });
        });


         // pricing
         $(document).ready(function () {
            $('#currency').change(function(){
                if($(this).find(":selected").text() == 'Free'){
                     $('#price').val("")
                     $('#price').attr("disabled", "disabled")
                }else{
                    $('#price').removeAttr("disabled")
                }
            });
        });

        // make ajax call to fetch subfields
        $(document).ready(function(){
          $( "#field, #fieldcontainer" ).on('click change', function( event ) {
              // event.preventDefault();
              console.log(`"${event.type.toUpperCase()}" event happened`)
              let subfieldslist = $("#subfieldslist");
              // clear all options on change
              subfieldslist.empty()
              let subfieldslisturl = subfieldslist.attr('src');

              let selectedfield = $("#field option:selected").val();
              
              $.ajax({
                   type: "GET",
                   url: subfieldslisturl + `?field=${selectedfield}`,
                   success: function(data)
                   {
                     //
                    for (i in data) {
                      let subfield = data[i]
                      var o = new Option(subfield.title, subfield.slug);
                      /// jquerify the DOM object 'o' so we can use the html method
                      $(o).html(subfield.title);
                      $("#subfieldslist").append(o);
                    }
                   
                   },
                   error: function(err){
                      // console.log(err)
                   }
              });
          });
        })


        // make ajax call to fetch authors
        $(document).ready(function(){
          $( "#field, #fieldcontainer" ).on('click change', function( event ) {
              // event.preventDefault();
              console.log(`"${event.type.toUpperCase()}" event happened`)
              
              let authors = $("#authors");
              // clear all options on change
              authors.empty()
              let subfieldslisturl = authors.attr('src');

              let selectedfield = $("#field option:selected").val();
              
              $.ajax({
                   type: "GET",
                   url: subfieldslisturl,
                   success: function(data)
                   {
                     //
                    for (i in data) {
                      let author = data[i]
                      var n = new Option(author.username, author.username);
                      /// jquerify the DOM object 'o' so we can use the html method
                      $(n).html(author.username);
                      $("#authors").append(n);
                    }
                   
                   },
                   error: function(err){
                      console.log(err)
                   }
              });
          });
        })



         // coauthors
        //  $(document).ready(function () {
        //     $('#currency').change(function(){
        //         if($(this).find(":selected").text() == 'Free'){
        //              $('#price').val("")
        //              $('#price').attr("disabled", "disabled")
        //         }else{
        //             $('#price').removeAttr("disabled")
        //         }
        //     });
        // });

    </script>
    <script type="text/javascript" src="{{ asset('themes/airdgereaders/js/coauthors.js') }}"></script>
    <script type="text/javascript" src="{{ asset('themes/airdgereaders/js/subfields.js') }}"></script>
@endpush


@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-counterfull">
        <div class="container">

          <div class="messages">
              @if ($errors->any())
                  <div class="row  mt-3">
                      <div class="col-md-12">
                          <div class="alert alert-warning alert-dismissable" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                              <h3 class="alert-heading font-size-h4 font-w400">Error!</h3>
                              @foreach ($errors->all() as $error)
                                  <p class="mb-0">{{ $error }}</p>
                              @endforeach
                          </div>
                      </div>
                  </div>
              @endif
          </div>


            <div class="row">                
    
               <!-- title -->
                <div class="col-md-12">
                     @include('partials.fancy_title', ['title' => 'Publish File', 'description' => 'Enter additional details below to publish your resource'])
                </div>
                <div class="col-md-12">
                  @include('partials.flash')
                </div>

                <div class="col-md-12">
                   <div class="container publish">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="publish-info pl-10 flex flex-col">
                                  <i class="fileicon {{ $uploadedFile->icon() }}"></i>
                                   <p>{{ $uploadedFile->filename }}</p>

                                   <a href="{{ route('resources.create.upload') }}" class="btn btn-default">Change file</a>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <form class="publish-form" method="post" action="{{route('resources.store.publish')}}">
                                  @csrf()
                                  <!-- title -->
                                  <div class="form-group clearfix">
                                    <label class="control-label col-sm-2" for="title">TITLE:</label>
                                    <div class="col-sm-10 ">          
                                      <input type="text" class="form-control h-20" id="title" placeholder="Enter title " name="title">
                                    </div>
                                  </div>
                                  <div class="form-group clearfix">
                                    <label class="control-label col-sm-2" for="type">TYPE:</label>
                                    <div class="col-sm-10">          
                                      <select placeholder="Choose document type" name="type" class="selectize">
                                          <option></option>
                                          @foreach($resourceTypes as $type)
                                             <option value="{{ $type->slug }}">{{$type->title}}</option>
                                          @endforeach
                                      </select>
                                    </div>
                                  </div>

                                  <!-- field -->
                                  <div class="form-group clearfix">
                                    <label class="control-label col-sm-2" for="field">FIELD:</label>
                                    <div class="col-sm-10" id="fieldcontainer">
                                      <select placeholder="Choose field" name="field" id="field" class="selectize">
                                          <option></option>
                                          @foreach($resourceFields as $field)
                                             <option value="{{ $field->slug }}">{{$field->title}}</option>
                                          @endforeach
                                      </select>
                                    </div>
                                  </div>

                                   <!-- sub field -->
                                  <div class="form-group clearfix">
                                    <label class="control-label col-sm-2" for="field">Sub Fields:</label>
                                    <div class="col-sm-10">

                                      <div class="tag-wrapper">
                                        <div class="tag-container" id="subfieldscontainer">
                                          <input list="subfieldslist" type="text" placeholder="Select or type new"  />  
                                        </div>
                                      </div>

                                      <!-- actual subfields input hidden -->
                                      <input value="" type="text" class="hidden form-control" id="subfields" name="sub_fields">


                                      <datalist id="subfieldslist" src="{{ route('subfields') }}">
                                          
                                      </datalist>
                                    </div>
                                  </div>
                                 

                                  <!-- authors -->
                                  <div class="form-group clearfix">
                                    <label class="control-label col-sm-2" for="authors">CO-AUTHOR(S):</label>
                                    <div class="col-sm-10">

                                      <!-- co authors holder -->
                                      <div class="tag-wrapper">
                                        <div class="tag-container" id="coauthorscontainer">
                                          <input list="authors" type="text" placeholder="Select or type new"  />  
                                        </div>
                                      </div>

                                      <!-- actual couthors input hidded -->
                                      <input value="" type="text" class="hidden form-control" id="coauthors" name="coauthors">

                                      <!-- coauthors data list -->
                                      <datalist id="authors" src="{{ route('authors') }}">
                                      </datalist>
                                    </div>
                                  </div>
                                
                                    <!-- overview  -->
                                  <div class="form-group clearfix">
                                    <label class="control-label col-sm-2" for="overview"> OVERVIEW: <span class="text-xs">(Abstract/Table of content )</span>:</label>
                                    <div class="col-sm-10 ">
                                      <textarea name="overview" class="form-control" rows="5" id="overview"></textarea>
                                    </div>
                                  </div>

                                  <!-- pricing -->
                                  <div class="form-group clearfix" id="pricing">
                                    <label class="control-label col-sm-2" for="price">PRICE:</label>
                                    <div class="col-sm-10 flex flex-row   ">

                                     <div class="currency">
                                          <select id="currency" name="currency">
                                              <option value="">Free</option>
                                              @foreach($currencies  as $currency)
                                                 <option value="{{ $currency->code }}">{{ $currency->name }}</option>
                                              @endforeach
                                          </select>
                                     </div>
                                      <div class="price">
                                          <input type="text" class="form-control disabled" disabled="" id="price" placeholder="Enter selling price" name="price">
                                      </div>

                                    </div>
                                  </div>

                                  <!-- page stat -->
                                  <div class="form-group clearfix" id="pricing">
                                    <label class="control-label col-sm-2" for="price">Pages:</label>
                                    <div class="col-sm-10 flex flex-row   ">

                                    <div class="currency">
                                        <input type="text" class="form-control "  id="price" placeholder="Number of pages" name="page_count">
                                    </div>
                                    <div class="price">
                                        <input type="text" class="form-control "  id="price" placeholder="Enter preview limit" name="preview_limit">
                                    </div>
                                    </div>
                                  </div>

                                  <div class="form-group clearfix">        
                                    <div class="col-sm-offset-2 col-sm-10">
                                      <input class="hidden" value="{{ $uploadedFile->filename }}" type="text" name="tmpfile_name">
                                      <!-- use this to fake form submission prevent on key enter -->
                                      <button type="submit" disabled style="display: none" aria-hidden="true"></button>
                                      <button type="submit" class="btn btn-default">Publish</button>
                                    </div>
                                  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

