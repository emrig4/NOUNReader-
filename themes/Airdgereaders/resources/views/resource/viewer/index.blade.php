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

    #pdfFileInput {
        display: none;
    }
</style>
@endpush

@section('breadcrumb')
@endsection

@php
    // Initialize empty state
    $base64_encode = "";
    $redirectUrl = "";
    
    if ($mainFile) {
        $hasPaidAccess = false;
        $user = auth()->check() ? auth()->user() : null;
        
        // Check if user already unlocked this resource in session
        $sessionRead = \Session::get($resource->slug);
        
        if ($sessionRead) {
            // Already paid/accessed in this session
            $hasPaidAccess = true;
        } elseif ($resource->price > 0) {
            // Paid resource - check wallet balance
            if ($user) {
                $ranc_per_onread = setting('ranc_per_onread') ?: 150;
                $walletBal = \App\Modules\Wallet\Http\Traits\WalletTrait::subscriptionWalletBalance();
                
                if ($walletBal >= $ranc_per_onread) {
                    // Has credits but still locked until they click read
                    // The read action will deduct and set session
                    $hasPaidAccess = false;
                    $redirectUrl = route('resources.read', $resource->slug);
                } else {
                    // Logged in but LOW credits - redirect to pricing
                    $redirectUrl = route('pricings.index');
                }
            } else {
                // Guest - redirect to login
                $redirectUrl = route('login');
            }
        } else {
            // Free resource - show full
            $hasPaidAccess = true;
        }
        
        // Only load from S3 if user has paid access
        if ($hasPaidAccess) {
            try {
                $file = \Storage::disk('s3')->get($mainFile->path);
                $base64_encode = base64_encode($file);
                \Log::info("Full PDF served: {$resource->slug}");
            } catch (\Exception $e) {
                \Log::warning("PDF S3 load failed: {$mainFile->path}");
                $base64_encode = "";
            }
        } else {
            // 🔒 LOCKED - No S3 access, no preview generation
            // User must purchase credits and click read to unlock
            \Log::info("PDF locked (no preview, no S3 read): {$resource->slug}");
        }
    }
@endphp

@section('content')
<div class="ereaders-main-section" id="app">
    <div class="col-md-12" style="height: 1200px">

        {{-- STATE 1: FULL PDF (Paid Access) --}}
        @if($mainFile && $base64_encode)
            <vue-pdf-air
                :preview_limit="0"
                base64="{!! $base64_encode !!}"
                pdfsrc="">
            </vue-pdf-air>

        {{-- STATE 2: LOCKED (No Access) --}}
        @elseif($mainFile)
            <div class="ereaders-error-text mt-20 text-center" style="width:100%">
                <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 10px; opacity: 0.5;">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="#23A455" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2a5 5 0 00-5 5v3H6a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2v-8a2 2 0 00-2-2h-1V7a5 5 0 00-5-5zm-3 8V7a3 3 0 016 0v3H9z"/>
                    </svg>
                </div>

                <h4>{{ $resource->title }}</h4>

                <p>
                    This Resource contains
                    <strong>{{ $resource->pages ?? 'multiple' }} pages</strong>.
                </p>
                <p>
                    
                    <strong>Access Resources</strong>.
                </p>
                <p class="text-muted">
                    Access is restricted.
                </p>

                <p>
                    <strong>TOP CREDIT</strong> to unlock full access. Read and download thousands of Noun Resources.
                </p>

                {{-- DYNAMIC REDIRECT BUTTON --}}
                @if(auth()->check())
                    {{-- Logged in user - check credits --}}
                    @php
                        $ranc_per_onread = setting('ranc_per_onread') ?: 150;
                        $walletBal = \App\Modules\Wallet\Http\Traits\WalletTrait::subscriptionWalletBalance();
                    @endphp
                    
                    @if($walletBal >= $ranc_per_onread)
                        {{-- Has credits - go to read page to unlock --}}
                        <a href="{{ route('resources.read', $resource->slug) }}" class="eraders-search-btn">
                           UNLOCK DOCUMENT
                            <i class="icon ereaders-right-arrow"></i>
                        </a>
                    @else
                        {{-- Low credits - go to pricing page --}}
                        <a href="{{ route('pricings.index') }}" class="eraders-search-btn">
                            UNLOCK DOCUMENT
                            <i class="icon ereaders-right-arrow"></i>
                        </a>
                    @endif
                @else
                    {{-- Guest - go to login page --}}
                    <a href="{{ route('login') }}" class="eraders-search-btn">
                        UNLOCK DOCUMENT
                        <i class="icon ereaders-right-arrow"></i>
                    </a>
                @endif
            </div>

        {{-- STATE 3: FILE NOT FOUND --}}
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
@endpush