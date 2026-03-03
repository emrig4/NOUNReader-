@push('css')
    @livewireStyles
@endpush

@extends('layouts.admin')
  @section('content')
    <section class="container">

        <!-- Actions -->
        <div class="card lg:flex p-4 mb-10">
            <h2 class="text-xl leading-tight">User - {{ $user->first_name }}</h2>
        </div>

       
        
    <div class="lg:flex mt-5">
        <div class="row">
            <div class="col-md-6">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('account::settings.update-picture')
                @endif 
            </div>

            <div class="col-md-6">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('account::settings.change-password')
                @endif 
            </div>
                     
        </div>

        <div class="row mt-10">
            <div class="col-md-6">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('account::settings.update-contact-info')
                @endif 
            </div>
            <div class="col-md-6">
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('account::settings.update-personal-information')
                @endif 
            </div>

        </div>
    </div>


 



    </section>

  
  @endSection
  

@push('js')
    <script>
      $( function() {
        $( "#dob" ).datepicker();
      } );
    </script>
    <script type="text/javascript" src="{{ asset('themes/airdgereaders/js/subfields.js') }}"></script>
    @livewireScripts
@endpush

