@extends('layouts.public', ['title' => 'About Us'])

@push('meta')
    <meta name="description" content="NOUN Reader is an academic resource platform that helps students access course summaries, TMA, POP past questions, and exam preparation materials in a simple and distraction-free environment."/>
    <meta property="title" content="Nounreader – About Us">

    <meta name="keywords" content="NOUN resources, TMA answers, POP past questions, NOUN summaries, study materials Nigeria, exam preparation, NOUN Reader">

    <meta property="og:title" content="Nounreader – Academic Resource Platform for NOUN Students">
    <meta property="og:description" content="Access organised course summaries, TMA, POP, and exam preparation materials designed to help NOUN students study smarter.">
    <meta property="og:url" content="https://nounreader.com/">
@endpush

@section('content')
<div class="ereaders-main-section ereaders-counterfull">
    <div class="container" style="width: 100%">

        <div class="row">

            <!-- LEFT SIDE CONTENT -->
            <div class="col-md-8">
                <div class="ereaders-about-us">

                    <h2><span class="ereaders-color">About Nounreader</span></h2>

                    <p>
                        Nounreader is an independent educational resource platform created to support students of the National Open University of Nigeria (NOUN).
                        It provides a structured and easy-to-use environment where students can read and download academic materials without stress.
                    </p>

                    <p>
                        Many students face challenges accessing scattered materials and navigating complex platforms.
                        Nounreader simplifies this process by offering organised and accessible study resources in one place.
                    </p>

                    <h2><span class="ereaders-color">What We Offer</span></h2>

                    <p>
                        The platform provides access to essential academic resources including:
                        <strong>course summaries</strong>,
                        <strong>POP past questions</strong>,
                        <strong>Tutor Marked Assignments (TMA)</strong>,
                        and <strong>exam preparation materials</strong>.
                    </p>

                    <p>
                        These resources are designed to help students spend less time searching and more time understanding their courses.
                    </p>

                    <h2><span class="ereaders-color">Why Use Nounreader?</span></h2>

                    <p>
                        Nounreader offers a clean, distraction-free environment that supports focused studying.
                        The platform is built to improve organisation, enhance understanding, and make exam preparation more effective.
                    </p>

                    <p>
                        With flexible access across devices, students can study anytime and anywhere without interruption.
                    </p>

                    <h2><span class="ereaders-color">Our Mission</span></h2>

                    <p>
                        Our mission is to provide a reliable, structured, and distraction-free learning platform that helps students study smarter and achieve better academic results.
                    </p>

                    <h2><span class="ereaders-color">Important Notice</span></h2>

                    <p>
                        Nounreader is an independent platform and is not affiliated with, endorsed by, or officially connected to the National Open University of Nigeria or any of its departments.
                    </p>

                    <p>
                        All materials are provided for educational and revision purposes only. Some content may be adapted or summarised to improve understanding.
                        Students are advised to refer to official NOUN platforms for original academic materials.
                    </p>

                </div>
            </div>

            <!-- RIGHT SIDE - YOUTUBE VIDEO -->
            <div class="col-md-4">
                <figure class="ereaders-about-thumb ereaders-video-container">
                    <div class="youtube-wrapper">
                        <iframe 
                            width="560" 
                            height="315" 
                            src="https://www.youtube.com/embed/i4NhlezEXLg?si=uSfSfLjfJQ97LtaH" 
                            title="Nounreader Introduction Video" 
                            frameborder="0" 
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