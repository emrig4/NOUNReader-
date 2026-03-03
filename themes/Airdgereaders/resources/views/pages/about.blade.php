@extends('layouts.public', ['title' => 'Browse Fields'])

@push('meta')
    <meta name="description" content="ReadProjectTopics is an academic database providing access to thousands of project topics and complete project materials for ND, NCE, HND, BSc, MSc, and PhD students."/>
    <meta property="title" content="ReadProjectTopics.com – Project Topics and Research Materials">

    <meta name="keywords" content="project topics, final year project topics, project materials, research materials, academic documents, project topics for students, Nigerian project topics, complete project materials PDF">

    <meta property="og:title" content="ReadProjectTopics.com – Project Topics and Academic Research Materials">
    <meta property="og:description" content="Access over 50,000 project topics and complete research materials across all academic disciplines at ReadProjectTopics.com.">
    <meta property="og:url" content="https://projectandmaterials.com/">
@endpush

@push('css')
@endpush

@section('content')
<div class="ereaders-main-section ereaders-counterfull">
    <div class="container" style="width: 100%">

        <div class="row">

            <!-- LEFT SIDE CONTENT -->
            <div class="col-md-8">
                <div class="ereaders-about-us">

                    <h2><span class="ereaders-color">What Is a Project Topic?</span></h2>

                    <p>
                        A project topic is a specific subject selected by a student for academic research.
                        It guides the entire project work by defining what the study is about, the problem
                        to be investigated, and the area of focus.
                    </p>

                    <p>
                        A complete project usually includes:
                        <strong>Chapter One</strong> (Introduction),
                        <strong>Chapter Two</strong> (Literature Review),
                        <strong>Chapter Three</strong> (Methodology),
                        <strong>Chapter Four</strong> (Data Analysis and Results),
                        and <strong>Chapter Five</strong> (Summary, Conclusion, and Recommendations).
                    </p>

                    <p>
                        Project topics are required for final-year projects, dissertations, and theses
                        in universities, polytechnics, and colleges of education.
                    </p>

                    <h2><span class="ereaders-color">About PAM</span></h2>

                    <p>
                        projectandmaterials is an online academic database that provides students with access
                        to thousands of project topics and complete project materials across all fields of study.
                        It serves as a central library where students can search, explore, and select suitable
                        research topics for their academic projects.
                    </p>

                    <p>
                        The platform is designed to make project work easier by helping students quickly find
                        relevant and researchable topics without stress. Topics are organized by discipline
                        and department for easy navigation. Students can search using keywords across
                        <strong>over 50,000 available project materials</strong>.
                    </p>

                    <h2><span class="ereaders-color">Why Use PAM?</span></h2>

                    <p>
                        A large database of project topics from all academic disciplines, easy search using
                        one or two keywords, suitable for ND, NCE, HND, BSc, BEd, MSc, and PhD students,
                        topics aligned with academic standards, saves time and reduces the stress of topic
                        selection, and supports students at every stage of project work.
                    </p>

                    <h2><span class="ereaders-color">Our Purpose</span></h2>

                    <p>
                        The purpose of projectandmaterials is to provide a reliable and easy-to-use academic
                        database that helps students choose better project topics and successfully complete
                        their research.
                    </p>

                    <a href="https://projectandmaterials.com/resources/fields" class="ereaders-simple-btn ereaders-bgcolor">
                        Browse Faculties <i class="fa fa-angle-right"></i>
                    </a>

                </div>
            </div>

              <!-- RIGHT SIDE - YOUTUBE VIDEO -->
            <div class="col-md-4">
                <figure class="ereaders-about-thumb ereaders-video-container">
                    {{-- Responsive YouTube Video Embed - Lazy Loaded for Performance --}}
                    <div class="youtube-wrapper">
                        <iframe 
                            width="560" 
                            height="315" 
                            src="https://www.youtube.com/embed/ydkvutyjjiA?si=ymz0Z2txlkRPDJ-c" 
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            referrerpolicy="strict-origin-when-cross-origin" 
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    </div>
                </figure>
            </div>

        </div>

    </div>
</div>
@endsection
