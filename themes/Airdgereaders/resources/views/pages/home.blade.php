@extends('layouts.public')
@push('meta')
<title>Project Topics & Materials | Download Academic Research</title>
<meta name="description" content="Largest online repository for project topics and materials in Nigeria. Download complete research materials, final year projects, thesis, and dissertations in PDF format.">
<meta name="keywords" content="project topics, project materials, final year projects, thesis, dissertation, research topics, academic resources, Nigerian university projects, free project downloads">

{{-- Open Graph Meta Tags --}}
<meta property="og:title" content="Project Topics & Materials | Download Academic Research">
<meta property="og:description" content="Largest online repository for project topics and materials in Nigeria. Download complete research materials for your academic success.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url('/') }}">
<meta property="og:image" content="{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}">
<meta property="og:site_name" content="Project Topics & Materials">

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Project Topics & Materials | Download Academic Research">
<meta name="twitter:description" content="Largest online repository for project topics and materials in Nigeria. Download complete research materials for your academic success.">
<meta name="twitter:image" content="{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ url('/') }}">

{{-- Robots --}}
<meta name="robots" content="index, follow">

{{-- Google Site Verification --}}
<meta name="google-site-verification" content="urfmtM4Blxx1obOP0-KybEZ8cXoHj4sLEv4sbBGeAJM" />

{{-- JSON-LD Schema Markup --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "Project Topics & Materials",
  "url": "{{ url('/') }}",
  "alternateName": "projectandmaterials",
  "description": "Largest online repository for project topics and materials in Nigeria. Download complete research materials, final year projects, thesis, and dissertations in PDF format.",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ url('/resources/searches?search={search_term_string}') }}",
    "query-input": "required name=search_term_string"
  },
  "publisher": {
    "@type": "Organization",
    "name": "Project Topics & Materials",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('themes/airdgereaders/images/Projectandmaterials.webp') }}"
    }
  },
  "inLanguage": "en"
}
</script>
@endpush

@push('css')
<style>
    /* Main H1 styling */
    .ereaders-work-learn h1 {
        font-size: 30px;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 15px;
        color: #23A455;
    }

    .ereaders-work-learn h1 span {
        color: inherit;
    }
    
    /* Fix heading hierarchy - convert h6 to h3 for proper SEO structure */
    .ereaders-service-grid h3 {
        font-size: 14px;
        font-weight: 600;
        margin: 0;
        line-height: 1.4;
    }
    
    .ereaders-service-grid h3 a {
        text-decoration: none;
        color: inherit;
    }
    
    .ereaders-service-grid h3 a:hover {
        color: #23A455;
    }
    
    /* Fix counter display styling */
    .ereaders-counter-text .numscroller {
        font-size: 36px;
        font-weight: 700;
        display: block;
        color: #23A455;
    }
    
    /* Fix testimonial heading hierarchy - convert h4 to h3 for proper SEO structure */
    .ereaders-testimonial-text h3 {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 5px 0;
        line-height: 1.4;
    }
    
    .ereaders-testimonial-text h3 a {
        text-decoration: none;
        color: inherit;
    }
    
    .ereaders-testimonial-text h3 a:hover {
        color: #23A455;
    }
</style>
@endpush
 
@section('banner')
    @include('partials.banner')
@endsection


@section('content')
    <!--// Main Section \\-->
    <!-- COUNTERS -->
<div class="col-md-12">
    <div class="ereaders-counter ereaders-about-counter">
        <ul>

            <li>
                <div class="ereaders-counter-text">
                    <i class="icon ereaders-document" aria-hidden="true"></i>
                    <div class="ereaders-scroller">
                        <span class="numscroller" data-min="0" data-max="25124" data-delay="10" data-increment="100">27045</span> 
                        <span>POP EXAM</span>
                    </div>
                </div>
            </li>

            <li>
                <div class="ereaders-counter-text">
                    <i class="icon ereaders-book" aria-hidden="true"></i>
                    <div class="ereaders-scroller">
                        <span class="numscroller" data-min="0" data-max="14510" data-delay="10" data-increment="100">14510</span> 
                        <span>TMA</span>
                    </div>
                </div>
            </li>

            <li>
                <div class="ereaders-counter-text">
                    <i class="icon ereaders-download-content" aria-hidden="true"></i>
                    <div class="ereaders-scroller">
                        <span class="numscroller" data-min="0" data-max="13550" data-delay="10" data-increment="100">13550</span> 
                        <span>E-EXAM</span>
                    </div>
                </div>
            </li>

            <li>
                <div class="ereaders-counter-text">
                    <i class="icon ereaders-document" aria-hidden="true"></i>
                    <div class="ereaders-scroller">
                        <span class="numscroller" data-min="0" data-max="11932" data-delay="10" data-increment="100">11932</span> 
                        <span>SUMMARIES</span>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</div>
    <!--// Main Section \\-->

    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-work-learnfull">
        <div class="container">
            <div class="row">

<div class="col-md-7">
    <div class="ereaders-work-learn">
        <h1>Read and download NOUN educational resources</h1>
        <p>
           <strong>NOUN Reader</strong> is designed to simplify how students access, organise, and study their academic resources. Many learners struggle with scattered materials, difficult navigation, and platforms filled with distractions. NOUN Reader provides a focused, easy-to-use environment where students can read and download educational resources seamlessly.
        </p>

        <p>
           Built with flexibility in mind, the platform allows students to conveniently access course materials, POP past questions, TMA resources, and exam preparation documents anytime, anywhere. The interface is structured to support efficient studying, helping learners spend less time searching and more time understanding their subjects.
        </p>

        <p>
          The platform prioritises concentration by providing a clean, ad-free environment, allowing students to focus fully on their studies without interruptions.NOUN Reader isn’t just a resource site — it’s a study support system designed to improve clarity, organisation, and academic readiness.
        </p>

        <a href="https://pamdev.online/resources/fields/noun-resources"
           class="ereaders-simple-btn ereaders-bgcolor">
           Get Started Now <i class="fa fa-angle-right" aria-hidden="true"></i>
        </a>
    </div>
</div>


            </div>
        </div>
    </div>
    <!--// Main Section \\-->

    <!--// Main Section services \\-->
    <div class="hidden ereaders-main-section ereaders-service-gridfull">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="ereaders-fancy-title">
                        <h2>Our Featured Services</h2>
                        <div class="clearfix"></div>
                        <p>Large online resource library where authors and academician meets.</p>
                    </div>

                    <!-- include services grid here -->
                </div>

            </div>
        </div>
    </div>
    <!--// Main Section \\-->

    <!--// Main Section Top Fields \\-->
    <!--<div class="ereaders-main-section ereaders-service-gridfull">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="ereaders-fancy-title">
                        <h2> Featured Fields</h2>
                        <div class="clearfix"></div>
                        <p>Large online resource library where authors and academician meets.</p>
                    </div>

                    <!-- include services grid here -->
                   <!--  @include('resource.partials.featured_fields_grid')

                    <a href="https://projectandmaterials.com/resources/fields" class="ereaders-simple-btn ereaders-bgcolor">Browse All Fields <i class="fa fa-angle-right text-white"></i></a>
                </div>

            </div>
        </div>
    </div>-->

 <!--// Main Section \\-->
    <div class="ereaders-main-section">
        <div class="container">
            <div class="row">

                <aside class="col-md-6"><div class="ereaders-author-thumb"><img src="{{theme_asset('extra-images/Nounreader.webp')}}" alt=""></div></aside>
                <aside class="col-md-6">
                    <div class="ereaders-author-text">
                        <h2><span>Disclaimer</span></h2>
                         
                        <p>NOUN Reader is an independent educational resource platform created to support students of the National Open University of Nigeria. This platform is not affiliated with, endorsed by, or officially connected to the National Open University of Nigeria or any of its departments.</p>  
                        <p>
                           All materials provided on this website, including past questions and study resources, are made available strictly for educational and revision purposes. 
                        </p>
                        <p>
                            The content is intended to assist students in their academic preparation and personal study.
                        </p>
                       
                        
                    </div>
                </aside>

            </div>
        </div>
    </div>
    <!--// Main Section \\-->
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-testimonialfull">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="ereaders-testimonial">
                        <div class="ereaders-testimonial-wrap">
                            <div class="ereaders-fancy-title"><h2>What People Say</h2></div>
                            <div class="ereaders-testimonial-slide">
                                <div class="ereaders-testimonial-slide-layer">
                                    <figure><img src="{{ asset('themes/airdgereaders/images/female.png') }}" alt="Jessica Mann - University of Abuja testimonial"></figure>
                                    <div class="ereaders-testimonial-text">
                                        <h3><a href="{{ url('/') }}">Jessica Mann</a></h3>
                                        <span>University of Abuja</span>
                                        <p>"projectandmaterials enable me to access different resources which includes Project, thesis, dissertation, journal and other resources which I was able to compare and gather informations related to my topic." </p>
                                    </div>
                                </div>
                                <div class="ereaders-testimonial-slide-layer">
                                    <figure><img src="{{ asset('themes/airdgereaders/images/female.png') }}" alt="Maria Okiptu - Federal University of Technology, Minna testimonial"></figure>
                                    <div class="ereaders-testimonial-text">
                                        <h3><a href="{{ url('/') }}">Maria Okiptu</a></h3>
                                        <span>Federal University of Technology, Minna</span>
                                        <p>" With projectandmaterials I was able to write my final year project topic as it makes the process more easier, it gives me access to  projects from various departments.</p>
                                    </div>
                                </div>
                                <div class="ereaders-testimonial-slide-layer">
                                    <figure><img src="{{ asset('themes/airdgereaders/images/male.png') }}" alt="JOSEPH KUREH - Imo State University testimonial"></figure>
                                    <div class="ereaders-testimonial-text">
                                        <h3><a href="{{ url('/') }}">JOSEPH KUREH</a></h3>
                                        <span>Imo State University</span>
                                        <p>"Would recommend projectandmaterials to others because  it was a good source to my research which make my work easier.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--// Main Section \\-->

    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-app-sectionfull">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="ereaders-fancy-title">
                        <h2>Read It In All Devices</h2>
                        <div class="clearfix"></div>
                        <p>Nounreader - Find the right resources.</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="ereaders-app-text">
                        <p>NOUN Reader is a modern academic resource platform built to enhance how students engage with their study materials. Recognising the challenges learners face with fragmented content and distracting interfaces, NOUN Reader delivers a structured, accessible, and distraction-free learning experience. </p>
                        <p>Students can conveniently read and download curated educational resources, including course materials, POP past questions, TMA references, and examination preparation documents. The platform’s design promotes organisation, efficiency, and focused study sessions.</p>
                        <p>By eliminating intrusive advertisements, NOUN Reader maintains a calm digital environment that supports deeper concentration and better retention. The mission of NOUN Reader is to provide flexibility, clarity, and reliability — empowering students to prepare smarter and study with confidence.</p>
                        <div class="">
                            <a href="#" class="ereaders-fancy-btn flex" rel="nofollow"><i class="icon ereaders-apple-logo" aria-hidden="true"></i> <span><small>GET IT ON</small><br> AppStore</span></a>
                            <a href="#" class="ereaders-fancy-btn flex" rel="nofollow"><i class="icon ereaders-play-store" aria-hidden="true"></i> <span><small>GET IT ON</small><br> GooglePlay</span></a>
                        </div>
                    </div>
                </div>
               <aside class="col-md-6">
                   <div class="ereaders-author-thumb">
                       <img src="{{ asset('themes/airdgereaders/images/Nounreader.webp') }}" alt="Project and Materials - Academic Resource Library">
                   </div>
               </aside>
                </div>

            </div>
        </div>
    </div>
    <!--// Main Section \\-->

   
@endsection
