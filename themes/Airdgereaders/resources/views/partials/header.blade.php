<header id="ereaders-header" class="ereaders-header-one">
    <div class="ereaders-main-header">
        <div class="container">
            <div class="row">
                <aside class="col-md-3">
                    <a href="/" class="logo">
                        <img src="{{ asset('themes/airdgereaders/images/nounreader-logo-main.svg') }}" alt="">
                    </a>
                </aside>
                <aside class="col-md-9 position-relative">
                    <a href="#menu" class="menu-link active"><span></span></a>
                    <div>
                        @include('partials.nav')

                        <!-- Fixed Search Icon -->
                        <div class="fixed-search-icon" data-toggle="modal" data-target="#modalPoll-1">
                            <svg class="svg-inline--fa fa-search fa-w-16 h-6" fill="#23A455" aria-hidden="true" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path>
                            </svg>
                        </div>

                        <div class="mobile-action-buttons d-flex flex-column flex-md-row align-items-center justify-content-end mt-3 mt-md-0">
                            <a href="https://projectandmaterials.com/search-topic"
                               class="ereaders-upload-btn ereaders-bgcolor h-8 mobile-btn mb-2 mb-md-0 mr-md-2"
                               style="color:#23A455;">
                                Search
                            </a>
                            <a href="{{ route('resources.submit') }}"
                               class="ereaders-upload-btn ereaders-bgcolor h-8 mobile-btn"
                               style="color:#23A455;">
                                Upload
                            </a>
                        </div>
                    </div>
                </aside>
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
</header>

@push('css')
<style>
/* Fixed search icon for both desktop and mobile */
.fixed-search-icon {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 10px;
    cursor: pointer;
    transition: box-shadow 0.2s;
}
.fixed-search-icon:hover {
    box-shadow: 0 4px 16px rgba(35,164,85,0.15);
    background: #23A455;
}
.fixed-search-icon svg {
    width: 28px;
    height: 28px;
    display: block;
    color: #23A455;
}
.fixed-search-icon:hover svg {
    color: #fff;
    fill: #fff;
}

/* Responsive action buttons */
.mobile-action-buttons {
    margin-top: 60px;
}
@media (min-width: 768px) {
    .mobile-action-buttons {
        margin-top: 0;
        flex-direction: row !important;
    }
    .mobile-action-buttons .mobile-btn {
        margin-bottom: 0 !important;
        margin-right: 10px;
    }
}
@media (max-width: 767px) {
    .fixed-search-icon {
        top: 10px;
        right: 10px;
        padding: 8px;
    }
    .mobile-action-buttons {
        flex-direction: column !important;
        align-items: flex-end !important;
        margin-top: 60px;
    }
    .mobile-action-buttons .mobile-btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endpush