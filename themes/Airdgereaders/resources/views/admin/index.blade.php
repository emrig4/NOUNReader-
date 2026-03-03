@extends('layouts.admin')
  @section('content')
    <section class="container">
      <div class="card p-5">
          <h3>STATS</h3>
          
          @include('admin.partials.stats')
      </div>
    </section>
  
  @endSection
  @push('js')
      <script src="{{ theme_asset('admin/assets/js/glide.min.js')}}"></script>
  @endPush


