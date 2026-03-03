{{-- ✅ NEW: SEO Meta Tags Component --}}

@if(isset($title))
    <meta property="og:title" content="{{ $title }}" />
    <meta name="twitter:title" content="{{ $title }}" />
@endif

@if(isset($description))
    <meta name="description" content="{{ $description }}" />
    <meta property="og:description" content="{{ $description }}" />
    <meta name="twitter:description" content="{{ $description }}" />
@endif

@if(isset($keywords))
    <meta name="keywords" content="{{ $keywords }}" />
@endif

@if(isset($image))
    <meta property="og:image" content="{{ $image }}" />
    <meta name="twitter:image" content="{{ $image }}" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
@else
    <meta property="og:image" content="{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}" />
    <meta name="twitter:image" content="{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}" />
@endif

@if(isset($url))
    <meta property="og:url" content="{{ $url }}" />
    <link rel="canonical" href="{{ $url }}" />
@else
    <meta property="og:url" content="{{ url()->current() }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
@endif

@if(isset($type))
    <meta property="og:type" content="{{ $type }}" />
@else
    <meta property="og:type" content="website" />
@endif

{{-- Additional SEO Meta Tags --}}
<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
<meta name="language" content="English">
<meta name="revisit-after" content="7 days">
<meta name="author" content="readprojecttopics">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:site" content="@readprojecttopics">
<meta name="twitter:creator" content="@readprojecttopics">