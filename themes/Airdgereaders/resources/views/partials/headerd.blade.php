// ...existing code...
<header id="ereaders-header" class="ereaders-header-one">
    <div class="ereaders-main-header">
        <div class="container">
            <div class="row">
                <div class="flex justify-between flex-wrap items-center w-100">
                    <aside class="col-md-3 col-6 d-flex align-items-center">
                        <a href="{{route('pages.home')}}" class="logo">
                            <img src="{{ asset('themes/airdgereaders/images/nounreader-logo-main.svg') }}" alt="">
                        </a>
                    </aside>
                    
                    <div class="col-md-9 col-6 d-flex justify-content-end align-items-center">
                        <div class="hidden-xs mr-3">
                            @include('partials.nav')
                        </div>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('resources.submit') }}" class="ereaders-upload-btn ereaders-bgcolor h-8 ml-2 d-none d-md-inline-block">Upload</a>
                            <button class="ereaders-upload-btn ereaders-bgcolor h-8 ml-2 d-inline-block d-md-none" style="padding: 0 16px;" onclick="window.location='{{ route('resources.submit') }}'">
                                <i class="fa fa-upload"></i>
                            </button>
                            <button class="icon-wrap mx-2 d-none d-md-inline-block" data-toggle="modal" data-target="#modalPoll-1" aria-label="Search">
                                <svg class="svg-inline--fa fa-search fa-w-16 h-6" fill="#ccc" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                                </svg>
                            </button>
                            <button class="icon-wrap mx-2 d-inline-block d-md-none" data-toggle="modal" data-target="#modalPoll-1" aria-label="Search">
                                <svg class="svg-inline--fa fa-search fa-w-16 h-6" fill="#23A455" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                                </svg>
                            </button>
                            <div class="icon-wrap mx-2" data-toggle="modal" data-target="#preferenceModal">
                                <img class="h-6" src="{{ asset('themes/airdgereaders/images/icons/globe-line.svg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- search Modal: modalPoll -->
                <div class="modal fade right" id="modalPoll-1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                  aria-hidden="true" data-backdrop="false">
                  <div class="modal-dialog modal-full-height modal-right modal-notify modal-info" role="document">
                    <div class="modal-content">
                        @include('partials.search_mega')
                    </div>
                  </div>
                </div>
                <!-- Modal: modalPoll -->
            </div>
        </div>
    </div>
</header>
@push('css')
<style>
    @media (max-width: 767px) {
        .ereaders-upload-btn.d-md-inline-block { display: none !important; }
        .ereaders-upload-btn.d-inline-block { display: inline-block !important; }
        .icon-wrap.d-md-inline-block { display: none !important; }
        .icon-wrap.d-inline-block { display: inline-block !important; }
        .hidden-xs { display: none !important; }
        .col-6 { width: 50%; }
        .col-md-9, .col-md-3 { width: 100%; }
        .d-flex.align-items-center { justify-content: flex-end; }
    }
    @media (min-width: 768px) {
        .ereaders-upload-btn.d-md-inline-block { display: inline-block !important; }
        .ereaders-upload-btn.d-inline-block { display: none !important; }
        .icon-wrap.d-md-inline-block { display: inline-block !important; }
        .icon-wrap.d-inline-block { display: none !important; }
        .hidden-xs { display: block !important; }
        .col-md-9 { width: 75%; }
        .col-md-3 { width: 25%; }
        .d-flex.align-items-center { justify-content: flex-end; }
    }
    .ereaders-upload-btn, .icon-wrap {
        vertical-align: middle;
    }
    .d-flex.align-items-center > * {
        margin-left: 8px;
        margin-right: 0;
    }
</style>
@endpush