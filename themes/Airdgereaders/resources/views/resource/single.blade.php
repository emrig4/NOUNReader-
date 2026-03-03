@extends('layouts.public', ['title' => ' ' . ($resource->title ?? 'Document Not Available') ])

@push('css')
<style>
    /* Simple fix: Override Bootstrap's text-justify class */
    .text-justify {
        text-align: left !important;
        text-align-last: left !important;
    }
    
    /* Target resource description area */
    .ereaders-rich-editor {
        text-align: left !important;
    }
    
    /* Target content below title in resource detail */
    .mb-5.max-h-72.overflow-hidden {
        text-align: left !important;
    }
</style>
@endpush

@push('meta')
    @if($resource)
        {{-- SEO Meta Tags --}}
        <title>{{ $resource->title }} | Project Topics & Materials</title>
        <meta name="description" content="{{ mb_strimwidth(strip_tags($resource->overview ?? 'Download complete project topics and materials for academic research'), 0, 160, '...') }}">
        <meta name="keywords" content="project topics and materials, final year project topics, read project materials, read projects, download complete project materials, project and materials, research materials, academic materials, project topics for university students, Nigerian project topics, free project topics, complete project topics PDF">
        
        {{-- Open Graph Meta Tags --}}
        <meta property="og:title" content="{{ $resource->title }} | Project Topics & Materials">
        <meta property="og:description" content="{{ mb_strimwidth(strip_tags($resource->overview ?? 'Download complete project topics and materials for academic research'), 0, 200, '...') }}">
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ url()->current() }}">
        @if($resource->cover_image)
        <meta property="og:image" content="{{ asset('storage/' . $resource->cover_image) }}">
        @else
        <meta property="og:image" content="{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}">
        @endif
        
        {{-- Twitter Card Meta Tags --}}
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $resource->title }} | Project Topics & Materials">
        <meta name="twitter:description" content="{{ mb_strimwidth(strip_tags($resource->overview ?? 'Download complete project topics and materials for academic research'), 0, 160, '...') }}">
        @if($resource->cover_image)
        <meta name="twitter:image" content="{{ asset('storage/' . $resource->cover_image) }}">
        @else
        <meta name="twitter:image" content="{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}">
        @endif
        
        {{-- Canonical URL --}}
        <link rel="canonical" href="{{ url()->current() }}">
        
        {{-- Article Schema for Structured Data --}}
        @if(isset($resource->created_at))
        <meta property="article:published_time" content="{{ $resource->created_at->toIso8601String() }}">
        @endif
        @if(isset($resource->updated_at))
        <meta property="article:modified_time" content="{{ $resource->updated_at->toIso8601String() }}">
        @endif
        
        {{-- Structured Data (Schema.org) for Rich Snippets - FIXED: Removed addslashes() which causes bad escape sequence errors --}}
        @php
            $sd_title = $resource->title ?? 'Project Topics and Materials';
            $sd_description = mb_strimwidth(strip_tags($resource->overview ?? 'Download complete project topics and materials for academic research'), 0, 160, '...');
            $sd_author = $resource->author_name ?? 'Projectandmaterials';
            $sd_image = $resource->cover_image ? asset('storage/' . $resource->cover_image) : asset('themes/airdgereaders/images/Projectandmaterials.webp');
            $sd_published = isset($resource->created_at) ? $resource->created_at->toIso8601String() : now()->toIso8601String();
            $sd_modified = isset($resource->updated_at) ? $resource->updated_at->toIso8601String() : now()->toIso8601String();
        @endphp
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "ScholarlyArticle",
            "headline": {{ json_encode($sd_title) }},
            "description": {{ json_encode($sd_description) }},
            "image": {{ json_encode($sd_image) }},
            "author": {
                "@type": "Person",
                "name": {{ json_encode($sd_author) }}
            },
            "publisher": {
                "@type": "Organization",
                "name": "Project Topics & Materials",
                "logo": {
                    "@type": "ImageObject",
                    "url": {{ json_encode(asset('themes/airdgereaders/images/Projectandmaterials.webp')) }}
                }
            },
            "datePublished": {{ json_encode($sd_published) }},
            "dateModified": {{ json_encode($sd_modified) }},
            "mainEntityOfPage": {
                "@type": "WebPage",
                "@id": {{ json_encode(url()->current()) }}
            }
        }
        </script>
    @endif
@endpush

@section('breadcrumb')
    @if($resource)
        {{ Breadcrumbs::render('resource', $resource) }}
    @endif
@endsection

@php
    // Lightweight file check - verify URL is valid without loading content (fast page load)
    $fileAvailable = false;
    
    if($mainFile && $resource){
        try {
            // Use HEAD request to check if file exists (fast, doesn't download content)
            $headers = @get_headers($mainFile->url(), 1);
            if($headers && isset($headers[0]) && strpos($headers[0], '200') !== false) {
                $fileAvailable = true;
            }
        } catch (\Exception $e) {
            \Log::warning("Document preview unavailable: " . $e->getMessage());
        }
    }
    
    // Extract keywords from [[keyword]] format in overview - look for raw [[keyword]] pattern
    $keywords = [];
    if($resource && $resource->overview){
        // Try to extract from raw overview field first (before HTML conversion)
        $rawOverview = $resource->overview ?? '';
        
        // If overview already has HTML links, try to extract keywords from the href attribute
        if (strpos($rawOverview, '<a href=') !== false) {
            preg_match_all('/<a[^>]*class="keyword-search-link"[^>]*>([^<]*)<\/a>/', $rawOverview, $matches);
            if (!empty($matches[1])) {
                $keywords = $matches[1];
            }
        }
        
        // Also try to find [[keyword]] pattern as fallback
        if (empty($keywords)) {
            preg_match_all('/\[\[(.*?)(?:\|.*?)?\]\]/', $rawOverview, $matches);
            if (!empty($matches[1])) {
                $keywords = $matches[1];
            }
        }
    }
@endphp

@section('content')
    <!--// Main Sections \\-->
    <div class="ereaders-main-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    @if($resource)
                        <!-- Resource Detail Content -->
                        <div class="ereaders-book-wrap">
                            <div class="row" id="app">
                                <div class="col-md-7 col-lg-push-5">
                                    @include('resource.partials.resource_detail', $resource)
                                </div>

                                <!-- File Preview -->
                                <div id="style-4-scrollbar" class="col-md-5 col-lg-pull-7" style="height: 500px; overflow-y: scroll;">
                                    @if($fileAvailable)
                                        <!-- File is available - Show Access Complete Project Materials message -->
                                        <div class="text-center py-5">
                                            <div style="font-size: 48px; color: #4F46E5; margin-bottom: 20px;">
                                                <i class="icon-book-open" aria-hidden="true"></i>
                                            </div>
                                            <h4 style="color: #374151; margin-bottom: 10px;"><strong>Read and  Download Noun Resource</strong></h4>
            <h6>ON</h6>
            <p>{{ $resource->title ?? 'Not available' }}</p>
        </li>
                                            <p style="color: #6B7280; font-size: 14px; line-height: 1.6; margin-bottom: 15px; max-width: 280px; margin-left: auto; margin-right: auto;">
                                           Instant Read and Download.
                                              <h6>Number of Pages</h6>
                @if($resource->pages)
                    <strong>{{ $resource->pages }} pages</strong>
                @else
                    <span class="text-muted">Pages not available</span>
                @endif
            </p>
                                            </p>
                                            
                                            {{-- KEYWORDS SECTION - Extract and display keywords from [[keyword]] format --}}
                                            @if(!empty($keywords))
                                                <div style="margin-top: 20px; margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; max-width: 280px; margin-left: auto; margin-right: auto;">
                                                    <h6 style="color: #2563eb; margin-bottom: 10px; font-size: 13px;">Related Search Topics:</h6>
                                                    <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                                                        @foreach($keywords as $keyword)
                                                            <a href="{{ route('resources.search') }}?search={{ urlencode(trim($keyword)) }}" 
                                                               style="display: inline-block; padding: 4px 12px; background: #2563eb; color: #ffffff; 
                                                                      border-radius: 20px; font-size: 12px; text-decoration: none; transition: all 0.3s ease;"
                                                               onmouseover="this.style.background='#1d4ed8'" 
                                                               onmouseout="this.style.background='#2563eb'">
                                                                {{ trim($keyword) }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- All Action Buttons - Left Aligned -->
                                            <div class="text-left" style="max-width: 280px; margin-left: auto; margin-right: auto;">
                                                <!-- Read button - visible to everyone -->
                                                <!-- For guests: store intended URL before redirecting to login -->
                                                @auth
                                                    <a href="{{ route('resources.read', $resource->slug) }}" class="ereaders-detail-btn btn-primary" style="display: inline-block; margin-bottom: 8px;">Read</a>
                                                @else
                                                    <a href="{{ route('login') }}?redirect_to={{ urlencode(route('resources.read', $resource->slug)) }}" class="ereaders-detail-btn btn-primary" style="display: inline-block; margin-bottom: 8px;">Read</a>
                                                @endauth

                                                <!-- Download button - NOW visible to everyone -->
                                                <!-- For guests: redirect to login with intended URL -->
                                                @auth
                                                    @if($resource->price > 0 && $resource->currency)
                                                        <a data-toggle="modal" data-target="#downloadresourcemodal" class="ereaders-detail-btn cursor-pointer btn-primary" style="display: inline-block; margin-bottom: 8px;">Download</a>
                                                    @else
                                                        <a href="{{ route('resources.freedownload', $resource->slug) }}" class="ereaders-detail-btn btn-primary" style="display: inline-block; margin-bottom: 8px;">Download</a>
                                                    @endif
                                                @else
                                                    {{-- Guest: redirect to login with intended URL, then to download --}}
                                                    @if($resource->price > 0 && $resource->currency)
                                                        <a href="{{ route('login') }}?redirect_to={{ urlencode(route('pricings.index')) }}" class="ereaders-detail-btn cursor-pointer btn-primary" style="display: inline-block; margin-bottom: 8px;">Download</a>
                                                    @else
                                                        <a href="{{ route('login') }}?redirect_to={{ urlencode(route('resources.freedownload', $resource->slug)) }}" class="ereaders-detail-btn btn-primary" style="display: inline-block; margin-bottom: 8px;">Download</a>
                                                    @endif
                                                @endauth

                                                <!-- These buttons always show -->
                                                <a href="{{ route('resources.cite', $resource->slug) }}" class="ereaders-detail-btn" style="display: inline-block; margin-bottom: 8px;">Cite</a>

                                                @if(auth()->user() && is_favorite($resource->id))
                                                    <a href="{{ route('account.favorites.remove', $resource->id) }}" class="ereaders-detail-btn" style="display: inline-block; margin-bottom: 8px;">Unsave</a>
                                                @else
                                                    <a href="{{ route('account.favorites.add', $resource->id) }}" class="ereaders-detail-btn" style="display: inline-block; margin-bottom: 8px;">Save</a>
                                                @endif

                                                @if(auth()->user())
                                                    <a data-toggle="modal" data-target="#reportresourcemodal" class="ereaders-detail-btn cursor-pointer" style="display: inline-block; margin-bottom: 8px;">Report</a>
                                                @endif

                                                @if(auth()->user())
                                                    @foreach($resource->authors as $author)
                                                        @if($author->is_lead && has_profile($author->username))
                                                            @if(is_follow($author->user->id))
                                                                <a href="{{ route('account.unfollow', $resource->author()) }}" class="btn btn-secondary bg-gray-400 text-white" style="display: inline-block; margin-bottom: 8px;">
                                                                    Unfollow <i class="icon mx-2 text-xs text-white">| {{ $author->user->followers->count() }}</i>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('account.follow', $author->user->id) }}" class="btn btn-primary" style="display: inline-block; margin-bottom: 8px;">
                                                                    Follow <i class="icon mx-2 text-xs text-white">| {{ $author->user->followers->count() }}</i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <!-- Document Preview Unavailable - Broken link detected -->
                                        <div class="text-center py-5">
                                            <div style="font-size: 48px; color: #dc2626; margin-bottom: 20px;">
                                                <i class="icon-book-open" aria-hidden="true"></i>
                                            </div>
                                            <h4 style="color: #dc2626; margin-bottom: 10px;">Document Preview Unavailable</h4>
                                            <p style="color: #6B7280; font-size: 14px; line-height: 1.6; margin-bottom: 15px; max-width: 280px; margin-left: auto; margin-right: auto;">
                                                This is a blog article. No document is attached.
                                            </p>
                                            
                                            {{-- KEYWORDS SECTION - Extract and display keywords from [[keyword]] format --}}
                                            @if(!empty($keywords))
                                                <div style="margin-top: 20px; margin-bottom: 20px; padding: 15px; background: #f8fafc; border-radius: 8px; max-width: 280px; margin-left: auto; margin-right: auto;">
                                                    <h6 style="color: #2563eb; margin-bottom: 10px; font-size: 13px;">Related Search Topics:</h6>
                                                    <div style="display: flex; flex-wrap: wrap; gap: 8px; justify-content: center;">
                                                        @foreach($keywords as $keyword)
                                                            <a href="{{ route('resources.search') }}?search={{ urlencode(trim($keyword)) }}" 
                                                               style="display: inline-block; padding: 4px 12px; background: #2563eb; color: #ffffff; 
                                                                      border-radius: 20px; font-size: 12px; text-decoration: none; transition: all 0.3s ease;"
                                                               onmouseover="this.style.background='#1d4ed8'" 
                                                               onmouseout="this.style.background='#2563eb'">
                                                                {{ trim($keyword) }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- All Action Buttons - Left Aligned -->
                                            <div class="text-left" style="max-width: 280px; margin-left: auto; margin-right: auto;">
                                                <!-- Read button - visible to everyone -->
                                                <!--<a href="{{ route('resources.read', $resource->slug) }}" class="ereaders-detail-btn btn-primary" style="display: inline-block; margin-bottom: 8px;">Read Project</a>

                                                <!-- Download button - only for logged-in users -->
                                                <!-- @auth
                                                    @if($resource->price > 0 && $resource->currency)
                                                        <a data-toggle="modal" data-target="#downloadresourcemodal" class="ereaders-detail-btn cursor-pointer btn-primary" style="display: inline-block; margin-bottom: 8px;">Download</a>
                                                    @else
                                                        <a href="{{ route('resources.freedownload', $resource->slug) }}" class="ereaders-detail-btn btn-primary" style="display: inline-block; margin-bottom: 8px;">Download</a>-->
                                                    @endif
                                                @endauth

                                                <!-- These buttons always show -->
                                                <a href="{{ route('resources.cite', $resource->slug) }}" class="ereaders-detail-btn" style="display: inline-block; margin-bottom: 8px;">Cite</a>

                                                @if(auth()->user() && is_favorite($resource->id))
                                                    <a href="{{ route('account.favorites.remove', $resource->id) }}" class="ereaders-detail-btn" style="display: inline-block; margin-bottom: 8px;">Unsave</a>
                                                @else
                                                    <a href="{{ route('account.favorites.add', $resource->id) }}" class="ereaders-detail-btn" style="display: inline-block; margin-bottom: 8px;">Save</a>
                                                @endif

                                                @if(auth()->user())
                                                    <a data-toggle="modal" data-target="#reportresourcemodal" class="ereaders-detail-btn cursor-pointer" style="display: inline-block; margin-bottom: 8px;">Report</a>
                                                @endif

                                                @if(auth()->user())
                                                    @foreach($resource->authors as $author)
                                                        @if($author->is_lead && has_profile($author->username))
                                                            @if(is_follow($author->user->id))
                                                                <a href="{{ route('account.unfollow', $resource->author()) }}" class="btn btn-secondary bg-gray-400 text-white" style="display: inline-block; margin-bottom: 8px;">
                                                                    Unfollow <i class="icon mx-2 text-xs text-white">| {{ $author->user->followers->count() }}</i>
                                                                </a>
                                                            @else
                                                                <a href="{{ route('account.follow', $author->user->id) }}" class="btn btn-primary" style="display: inline-block; margin-bottom: 8px;">
                                                                    Follow <i class="icon mx-2 text-xs text-white">| {{ $author->user->followers->count() }}</i>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            @include('resource.partials.resource_description')
                        </div>

                        <!-- Book Info Card -->
                        <div>
                            @include('resource.partials.inc.info_card', ['resource' => $resource])
                        </div>

                        <!-- Reviews -->
                        @include('resource.partials.resource_reviews', ['resource' => $resource])

                        <!-- Related Resources -->
                        @include('resource.partials.resource_related', ['related' => $resource->related()])
                    @else
                        <!-- Document Not Available Message -->
                        <div class="ereaders-book-wrap">
                            <div class="text-center py-5">
                                <div class="ereaders-detail-thumb-text">
                                    <h2>Document Not Available</h2>
                                    <div class="clearfix"></div>
                                    <div class="mb-5" style="text-align: left;">
                                        <p class="text-muted">This document may be under review or no longer available.</p>
                        <p>Publishing project materials helps you establish your position as an expert in your field of knowledge. </p>
                        <p>The solid body of the published projects will help you advance your career as you will be subject to academic appointments and promotions, also helps you establish your position as an expert in your field of knowledge and preserve your work in the permanent records of research database.</p>
                        <p>Published works can contribute to the general understanding of  research questions. </p>
                                        <aside class="col-md-6"><div class="ereaders-author-thumb"><img src="{{ asset('themes/airdgereaders/images/rpt.png') }}" alt=""></div></aside>
                                        <!-- Action Buttons - Left Aligned -->
                                        <div class="text-left" style="margin-top: 15px;">
                                             <a href="{{ route('resources.index') }}" class="ereaders-detail-btn">Browse Other Documents</a>
                                        </div>

                    </div>
                    </div>
                </div>
                                    </div>
                                    
                                    <ul class="ereaders-detail-social">
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-12"></div>
            </div>
        </div>
    </div>
    <!--// Main Section \\-->
@endsection

@push('js')
    <script src="{{ mix('/js/app.js') }}"></script>
@endpush