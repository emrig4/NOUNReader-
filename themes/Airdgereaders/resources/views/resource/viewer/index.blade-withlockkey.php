@extends('layouts.reader', ['title' => 'Resource | ' . $resource->title ])
@push('css')
    <style type="text/css">
        #viewerContainer::-webkit-scrollbar-track {
          -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        #viewerContainer::-webkit-scrollbar {
          height: 3px;
          width: 5px;
        }

        #viewerContainer::-webkit-scrollbar-thumb {
          background-color: #23A455;
          border-radius: 10px;
        }

        /*hide vue-pdf-app file input field*/
        #pdfFileInput{
          display: none;
        }
    </style>
@endpush

@section('breadcrumb')
@endsection

@php
    // SECURE PDF PREVIEW: Check access BEFORE loading full PDF
    // âœ… Works on MOBILE and DESKTOP - same logic for both
    if($mainFile){
        
        $sessionRead = \Session::get($resource->slug);
        $user = auth()->check() ? auth()->user() : null;
        
        // Determine if user has paid access
        $hasPaidAccess = false;
        
        if ($resource->price && $resource->price > 0) {
            // Paid resource
            if ($sessionRead) {
                // Already paid/accessed in this session
                $hasPaidAccess = true;
            } elseif ($user) {
                // Check wallet balance
                $ranc_per_onread = setting('ranc_per_onread') ?: 150;
                $walletBal = \App\Modules\Wallet\Http\Traits\WalletTrait::subscriptionWalletBalance();
                if ($walletBal >= $ranc_per_onread) {
                    // Has enough credits - SHOW PREVIEW FIRST
                    $hasPaidAccess = false;
                } else {
                    // Insufficient funds
                    $hasPaidAccess = false;
                }
            } else {
                // Guest user - SHOW PREVIEW
                $hasPaidAccess = false;
            }
        } else {
            // Free resource - SHOW FULL
            $hasPaidAccess = true;
        }
        
        if ($hasPaidAccess) {
            // âœ… PAID USER or FREE RESOURCE: Load complete PDF
            try {
                $file = \Storage::disk('s3')->get($mainFile->path);
                $base64_encode = base64_encode($file);
                $isLimit = 0;
                \Log::info("Full PDF served to: " . ($user ? $user->email : 'guest'));
            } catch (\Exception $e) {
                $base64_encode = "";
                $file = null;
                $isLimit = 0;
                \Log::warning('PDF viewer: S3 access failed for: ' . $mainFile->path);
            }
        } else {
            // âœ… GUEST or INSUFFICIENT FUNDS: Generate secure truncated preview
            try {
                // Get file content from S3
                $originalContent = \Storage::disk('s3')->get($mainFile->path);
                
                // Generate preview (3 pages + 1 overlay = 4 pages total)
                $pdfService = new \App\Services\PdfPreviewService();
                $previewContent = $pdfService->generateSecurePreview($originalContent, 3);
                
                if ($previewContent) {
                    $file = $previewContent;
                    $base64_encode = base64_encode($file);
                    $isLimit = 0; // PDF already has overlay built-in
                    \Log::info("Secure preview served (mobile & desktop): {$resource->slug}");
                } else {
                    throw new \Exception("Preview generation returned null");
                }
            } catch (\Exception $e) {
                // Fallback: Show error instead of full document
                \Log::warning('Secure preview failed: ' . $e->getMessage());
                $base64_encode = "";
                $file = null;
                $isLimit = 0;
            }
        }
        
    } else {
        $base64_encode = "";
        $file = null;
        $isLimit = 0;
    }
@endphp

@section('content')
<div class="ereaders-main-section" id="app">
    <div class="col-md-12" style="height: 1200px">

        {{-- STATE 1: PDF or Preview Loaded --}}
        @if($mainFile && $base64_encode)

            <vue-pdf-air 
                :preview_limit="{{ $isLimit }}" 
                base64="{!! $base64_encode !!}" 
                pdfsrc="">
            </vue-pdf-air>

        {{-- STATE 2: FILE EXISTS BUT LOCKED --}}
        @elseif($mainFile)

            <div class="ereaders-error-text mt-20 text-center" style="width:100%">

                {{-- LOCK ICON --}}
                <div style="font-size:64px; opacity:0.35; margin-bottom:10px;">
                    ðŸ”’
                </div>

                <h4>{{ $resource->title }}</h4>

                <p>
                    <strong>Document Available</strong>
                </p>

                <p>
                    This document contains
                    <strong>
                        {{ $resource->pages ?? 'multiple' }} pages
                    </strong>.
                </p>

                <p class="text-muted">
                    Preview is limited to only login users.
                </p>

                <p>
                    <strong>Login or Buy credit</strong> to unlock full access and download complete project.
                </p>

                <a href="{{ route('login') }}" class="eraders-search-btn">
                    UNLOCK DOCUMENT
                    <i class="icon ereaders-right-arrow"></i>
                </a>

            </div>

        {{-- STATE 3: FILE DOES NOT EXIST --}}
        @else

            <div class="ereaders-error-text mt-20 text-center">
                <span>FILE NOT AVAILABLE</span>
            </div>

        @endif

    </div>
</div>
@endsection


@push('js')
    <script src="{{ mix('/js/app.js') }}"></script>

    <script>
      var elem = document.getElementById("viewerContainer");
      function openFullscreen() {
        if (elem.requestFullscreen) {
          elem.requestFullscreen();
        } else if (elem.mozRequestFullScreen) {
          elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
          elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
          elem.msRequestFullscreen();
        }
      }
    </script>
@endpush