@extends('layouts.public')
@push('meta')
    <meta name="description" content="Complete project writing guide with 200+ frequently asked questions. Learn about project topics, materials, research design, chapters, defense, and more."/>
    
    <meta property="title" content="Project Writing Guide | ReadProjectTopics.com">
    
    <meta name="keywords" content="project writing guide, research project questions, how to write project, project topics selection, chapter one writing, chapter five recommendations, project defense questions">

    <meta property="og:title" content="Project Writing Guide | ReadProjectTopics.com">
    <meta property="og:description" content="Complete guide with 200+ questions on project writing, topic selection, research methodology, chapters, and defense preparation.">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>Project Writing Guide - 200+ Questions Answered | ReadProjectTopics.com</title>

@endpush

@push('css')
<style>
    .guide-section {
        margin-bottom: 50px;
    }
    .guide-section h3 {
        color: #23A455;
        border-bottom: 2px solid #23A455;
        padding-bottom: 10px;
        margin-bottom: 25px;
    }
    .guide-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .guide-list li {
        margin-bottom: 0;
    }
    .guide-list .ereaders-service-grid-text {
        padding: 15px 20px;
    }
    .guide-list .ereaders-service-grid-text h6 {
        font-size: 14px;
        line-height: 1.5;
        margin: 0;
    }
    .guide-list .ereaders-service-grid-text h6 a {
        display: block;
        color: #333;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    .guide-list .ereaders-service-grid-text h6 a:hover {
        color: #23A455;
    }
</style>
@endpush
 
@section('banner')
    @include('partials.banner')
@endsection


@section('content')
    <!--// Main Section \\-->
    <div class="ereaders-main-section ereaders-service-gridfull">
        <div class="container">
            <div class="row">

                <div class="col-md-12">
                    <div class="ereaders-fancy-title">
                        <h2>Project Writing Guide</h2>
                        <div class="clearfix"></div>
                        <p>200+ frequently asked questions about project writing, topic selection, research methodology, chapters, and defense preparation</p>
                    </div>

                    <!-- Category 1: Project Topic Selection -->
                    <div class="guide-section">
                        <h3>1. Project Topic Selection</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'choose good project topic']) }}">How do I choose a good project topic?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'acceptable project topic']) }}">What makes a project topic acceptable?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'narrow broad project topic']) }}">How do I narrow a broad project topic?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'align project topic with course']) }}">How do I align my project topic with my course?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'researchable project topic']) }}">How do I know if a project topic is researchable?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'change approved project topic']) }}">How do I change an approved project topic?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'same project topic two students']) }}">Can two students use the same project topic?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'strong project title']) }}">How do I write a strong project title?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'project topic vs project title']) }}">What is the difference between a project topic and a project title?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'project title length']) }}">How long should a project title be?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'avoid rejected project topics']) }}">How do I avoid rejected project topics?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'supervisors approve project topics']) }}">How do supervisors approve project topics?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'undergraduate project topics']) }}">What topics are suitable for undergraduate projects?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'current research topic']) }}">How do I select a current research topic?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'real organization project']) }}">Can I use a real organization for my project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'project materials research']) }}">What are project materials in research?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 2: Materials & Sources -->
                    <div class="guide-section">
                        <h3>2. Materials & Sources</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'reliable project materials']) }}">Where can I get reliable project materials?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'evaluate project materials quality']) }}">How do I evaluate project materials quality?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'primary secondary materials']) }}">What is the difference between primary and secondary materials?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'past projects materials']) }}">Can past projects be used as materials?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'recent project materials']) }}">How recent should project materials be?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'organize project materials']) }}">How do I organize project materials effectively?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'cite project materials']) }}">How do I cite project materials correctly?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'online sources project']) }}">Can online sources be used as project materials?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'avoid plagiarism materials']) }}">How do I avoid plagiarism when using materials?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'academically acceptable material']) }}">What makes a material academically acceptable?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'materials needed for project']) }}">How many materials are needed for a project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'store project materials']) }}">How do I store project materials digitally?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 3: Research Problem & Objectives -->
                    <div class="guide-section">
                        <h3>3. Research Problem & Objectives</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'identify research problem']) }}">How do I identify a research problem?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'identify research gap']) }}">How do I identify a research gap in project work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'convert problem research questions']) }}">How do I convert a problem into research questions?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'problem statement vs research gap']) }}">What is the difference between problem statement and research gap?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'valid research problem']) }}">How do I know if a research problem is valid?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'multiple problems project']) }}">Can a project have more than one problem?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'justify research problem']) }}">How do I justify a research problem?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'research problem mistakes']) }}">What are common mistakes in writing research problems?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'number of objectives']) }}">How many objectives should a project have?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'align objectives research questions']) }}">How do I align objectives with research questions?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'general specific objectives']) }}">What is the difference between general and specific objectives?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'measurable research objectives']) }}">How do I write measurable research objectives?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'objectives change research']) }}">Can objectives change during research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'objectives guide data analysis']) }}">How do objectives guide data analysis?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 4: Scope, Limitations & Significance -->
                    <div class="guide-section">
                        <h3>4. Scope, Limitations & Significance</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'scope vs limitation']) }}">What is the difference between scope and limitation?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write project scope']) }}">How do I write project scope clearly?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'acceptable limitations research']) }}">What are acceptable limitations in research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'present delimitations']) }}">How do I present delimitations in a project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'limitation reduce quality']) }}">Does limitation reduce project quality?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'significance study important']) }}">Why is significance of study important?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'identify beneficiaries study']) }}">How do I identify beneficiaries of a study?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'significance vs justification']) }}">What is the difference between significance and justification?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write significance']) }}">How do I write significance without exaggeration?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 5: Literature Review -->
                    <div class="guide-section">
                        <h3>5. Literature Review</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'sources literature review']) }}">How many sources should literature review contain?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'organize literature review']) }}">How do I organize literature review logically?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'review vs summary']) }}">What is the difference between review and summary?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'synthesize literature']) }}">How do I synthesize literature effectively?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'plagiarism literature review']) }}">How do I avoid plagiarism in literature review?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'tense literature review']) }}">What tense should be used in literature review?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 6: Theoretical Framework -->
                    <div class="guide-section">
                        <h3>6. Theoretical Framework</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'theoretical framework research']) }}">What is a theoretical framework in research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'theory vs model']) }}">What is the difference between theory and model?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'choose relevant theory']) }}">How do I choose a relevant theory?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'multiple theories project']) }}">Can a project have multiple theories?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'conceptual framework diagram']) }}">What is a conceptual framework diagram?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'variables conceptual framework']) }}">How do variables relate in a conceptual framework?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 7: Research Design -->
                    <div class="guide-section">
                        <h3>7. Research Design & Methodology</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'qualitative quantitative research']) }}">What is the difference between qualitative and quantitative research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'choose research design']) }}">How do I choose a research design?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'survey research design']) }}">What is a survey research design?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'experimental research design']) }}">What is experimental research design?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'descriptive research']) }}">When is descriptive research appropriate?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'justify research design']) }}">How do I justify my research design?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 8: Population & Sampling -->
                    <div class="guide-section">
                        <h3>8. Population & Sampling</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'population vs sample']) }}">What is the difference between population and sample?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'determine sample size']) }}">How do I determine sample size?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'sampling error']) }}">What is sampling error?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'probability non-probability sampling']) }}">What is the difference between probability and non-probability sampling?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'select respondents ethically']) }}">How do I select respondents ethically?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 9: Data Collection -->
                    <div class="guide-section">
                        <h3>9. Data Collection Instruments</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'data collection instruments']) }}">What are data collection instruments?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'design questionnaire']) }}">How do I design a questionnaire?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'questionnaire vs interview']) }}">What is the difference between questionnaire and interview?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'pilot test instrument']) }}">How do I pilot test a research instrument?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'observation method research']) }}">What is observation method in research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'administer questionnaires']) }}">How do I administer questionnaires properly?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 10: Validity & Reliability -->
                    <div class="guide-section">
                        <h3>10. Validity & Reliability</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'validity research']) }}">What is validity in research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'types validity project']) }}">What are the types of validity in project work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'reliability instrument']) }}">What is reliability of a research instrument?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'test reliability statistically']) }}">How do I test reliability statistically?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'validity vs reliability']) }}">What is the difference between validity and reliability?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 11: Data Analysis -->
                    <div class="guide-section">
                        <h3>11. Data Analysis</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'data analysis techniques']) }}">How do I choose data analysis techniques?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'interpret tables charts']) }}">How do I interpret tables and charts?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'match objectives analysis']) }}">How do I match objectives with analysis?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'research hypothesis']) }}">What is a research hypothesis?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'null alternative hypothesis']) }}">What is the difference between null and alternative hypothesis?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'hypotheses tested']) }}">When should hypotheses be tested?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'hypotheses acceptable']) }}">How many hypotheses are acceptable?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'statistical tools hypotheses']) }}">What statistical tools test hypotheses?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 12: Discussion & Conclusions -->
                    <div class="guide-section">
                        <h3>12. Discussion & Conclusions</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'compare findings literature']) }}">How do I compare findings with literature?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'tense discussion section']) }}">What tense is used in the discussion section?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'explain unexpected findings']) }}">How do I explain unexpected findings?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'findings vs discussion']) }}">What is the difference between findings and discussion?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'draw conclusions findings']) }}">How do I draw conclusions from findings?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'recommendations project']) }}">How many recommendations should a project have?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'practical recommendations']) }}">How do I write practical recommendations?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'conclusion vs summary']) }}">What is the difference between conclusion and summary?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'realistic recommendations']) }}">How do I ensure recommendations are realistic?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 13: Referencing -->
                    <div class="guide-section">
                        <h3>13. Referencing & Citations</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'referencing style']) }}">What referencing style should I use?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'APA MLA referencing']) }}">What is the difference between APA and MLA referencing?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'in-text citations']) }}">How do I format in-text citations?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'reference list']) }}">What is a reference list?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'bibliography vs references']) }}">What is the difference between bibliography and references?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'arrange references']) }}">How do I arrange references alphabetically?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 14: Abstract & Preliminaries -->
                    <div class="guide-section">
                        <h3>14. Abstract & Preliminaries</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'abstract writing tense']) }}">What tense is used in abstract writing?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'abstract length']) }}">How long should an abstract be?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'abstract executive summary']) }}">What is the difference between abstract and executive summary?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'before chapter one']) }}">What comes before chapter one?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write acknowledgements']) }}">How do I write acknowledgements properly?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 15: Project Defense -->
                    <div class="guide-section">
                        <h3>15. Project Defense</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'prepare project defense']) }}">How do I prepare for project defense?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'defense questions']) }}">What are common project defense questions?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'examiners look defense']) }}">What do examiners look for in project defense?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'answer defense questions']) }}">How do I answer defense questions confidently?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 16: Research Ethics -->
                    <div class="guide-section">
                        <h3>16. Research Ethics</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'research ethics']) }}">What is research ethics?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'informed consent']) }}">How do I get informed consent?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'data fabrication']) }}">Can data be fabricated in project work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'protect respondents privacy']) }}">How do I protect respondents' privacy?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'publish results']) }}">Can project results be published?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 17: Project Structure -->
                    <div class="guide-section">
                        <h3>17. Project Structure & Organization</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'introduction project report']) }}">What should be included in the introduction of a project report?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'clear introduction project']) }}">How do I write a clear introduction for a project work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'purpose introduction']) }}">What is the purpose of the introduction in a project report?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'gather materials project']) }}">How do I gather materials for a project report?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'suitable sources materials']) }}">What sources are suitable for gathering project materials?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'evaluate materials']) }}">How do I evaluate materials before using them in a project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'arrange materials logically']) }}">How do I arrange project materials logically?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><aresources.searches', ['search' => 'structure project report']) }}">How do I structure a href="{{ route(' project report correctly?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'organize project content']) }}">What is the best way to organize project content?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 18: Chapters Writing -->
                    <div class="guide-section">
                        <h3>18. Chapters Writing Guide</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'introduction section project']) }}">What should the introduction section of a project contain?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'introduction vs background']) }}">How is the introduction section different from background?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write background section']) }}">How do I write the background section of a project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'background study']) }}">What information belongs in the background of a study?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'specification design project']) }}">What is specification and design in project work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write specification design']) }}">How do I write the specification and design section?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'present results project']) }}">How do I present results in a project report?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'evaluation project work']) }}">What is evaluation in project work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'evaluate results academically']) }}">How do I evaluate project results academically?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'future work project']) }}">What is future work in a project report?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'suggestions future research']) }}">How do I write suggestions for future research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'future work importance']) }}">Why is future work important in project writing?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'strong conclusion project']) }}">How do I write a strong conclusion for a project report?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'project conclusion include']) }}">What should a project conclusion include?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'reflection project report']) }}">What is reflection in a project report?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write reflection']) }}">How do I write a reflection section academically?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'reflection compulsory']) }}">Is reflection compulsory in project work?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 19: Chapter One -->
                    <div class="guide-section">
                        <h3>19. Chapter One (Introduction)</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'chapter one project writing']) }}">What is Chapter One in project writing?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write Chapter One']) }}">How do I write Chapter One of a project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter One contain']) }}">What should Chapter One contain in a research project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter One pages']) }}">How many pages should Chapter One be?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'change Chapter One']) }}">Can I change Chapter One after supervisor approval?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'background study Chapter One']) }}">How do I write background to the study in Chapter One?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter One undergraduate']) }}">What is included in Chapter One of undergraduate projects?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'statement problem Chapter One']) }}">How do I write statement of the problem in Chapter One?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter One mistakes']) }}">What mistakes should I avoid in Chapter One?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 20: Chapter Two -->
                    <div class="guide-section">
                        <h3>20. Chapter Two (Literature Review)</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'chapter two project writing']) }}">What is Chapter Two in project writing?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write Chapter Two']) }}">How do I write Chapter Two of a research project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Two contain']) }}">What should Chapter Two contain?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Two sources']) }}">How many sources are required in Chapter Two?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'old journals Chapter Two']) }}">Can I use old journals in Chapter Two?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'organize Chapter Two']) }}">How do I organize Chapter Two properly?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter One vs Chapter Two']) }}">What is the difference between Chapter One and Chapter Two?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'plagiarism Chapter Two']) }}">How do I avoid plagiarism in Chapter Two?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Two pages']) }}">How many pages should Chapter Two be?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 21: Chapter Three -->
                    <div class="guide-section">
                        <h3>21. Chapter Three (Methodology)</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'chapter three project writing']) }}">What is Chapter Three in project writing?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write Chapter Three']) }}">How do I write Chapter Three of a project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Three contain']) }}">What should Chapter Three contain in research work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'research design Chapter Three']) }}">How do I choose research design for Chapter Three?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Three data collection']) }}">Can Chapter Three be changed after data collection?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'population sample Chapter Three']) }}">How do I write population and sample in Chapter Three?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Three instruments']) }}">What instruments are written in Chapter Three?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Three pages']) }}">How many pages should Chapter Three be?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Three mistakes']) }}">What common mistakes occur in Chapter Three?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 22: Chapter Four -->
                    <div class="guide-section">
                        <h3>22. Chapter Four (Data Analysis)</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'chapter four project writing']) }}">What is Chapter Four in project writing?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write Chapter Four']) }}">How do I write Chapter Four of a project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Four contain']) }}">What should Chapter Four contain?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'present data Chapter Four']) }}">How do I present data in Chapter Four?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'statistical tools Chapter Four']) }}">What statistical tools are used in Chapter Four?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Four discussion']) }}">Can Chapter Four contain discussion of findings?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'interpret results Chapter Four']) }}">How do I interpret results in Chapter Four?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Four tables']) }}">How many tables are allowed in Chapter Four?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Three vs Chapter Four']) }}">What is the difference between Chapter Three and Chapter Four?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 23: Chapter Five -->
                    <div class="guide-section">
                        <h3>23. Chapter Five (Summary & Recommendations)</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'chapter five project writing']) }}">What is Chapter Five in project writing?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'write Chapter Five']) }}">How do I write Chapter Five of a research project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Five contain']) }}">What should Chapter Five contain?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'recommendations Chapter Five']) }}">How do I write recommendations in Chapter Five?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'contribution knowledge']) }}">What is contribution to knowledge in Chapter Five?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Five limitations']) }}">Can Chapter Five include limitations of the study?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Five pages']) }}">How many pages should Chapter Five be?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'Chapter Four vs Chapter Five']) }}">What is the difference between Chapter Four and Chapter Five?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'further research Chapter Five']) }}">How do I write areas for further research in Chapter Five?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 24: Postgraduate Specific -->
                    <div class="guide-section">
                        <h3>24. Postgraduate Research Guide</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'postgraduate cover page']) }}">What is included on a postgraduate project cover page?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'declaration page']) }}">How do I write a declaration page for a thesis or dissertation?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'certification postgraduate']) }}">What is certification in postgraduate project work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'dedication page']) }}">What is the purpose of a dedication page in research work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'postgraduate acknowledgements']) }}">How do I write acknowledgements for a postgraduate thesis?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'postgraduate abstract']) }}">How is an abstract structured for postgraduate research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'postgraduate table of contents']) }}">What should be included in a postgraduate table of contents?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'list of tables thesis']) }}">How do I prepare a list of tables for a thesis?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'list of figures']) }}">How do I format a list of figures in research work?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'abbreviations project']) }}">When is a list of abbreviations required in a project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'postgraduate references']) }}">How are references arranged in postgraduate research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'appendices thesis']) }}">What materials belong in the appendices of a thesis?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'standard structure postgraduate']) }}">What is the standard structure of postgraduate research?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'undergraduate vs postgraduate']) }}">How is a postgraduate thesis different from an undergraduate project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'formatting requirements']) }}">What are postgraduate school formatting requirements?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Category 25: General Project Questions -->
                    <div class="guide-section">
                        <h3>25. General Project Questions</h3>
                        <div class="ereaders-service ereaders-service-grid">
                            <ul class="row guide-list">
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'project rejection']) }}">What causes project rejection?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'project duration']) }}">How long does a project take to complete?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'plagiarism consequences']) }}">What happens if project work is plagiarized?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'redo rejected project']) }}">Can I redo a rejected project?</a></h6>
                                    </div>
                                </li>
                                <li class="col-md-3 mb-0" style="margin-bottom: 5px; padding: 0 5px;">
                                    <div class="ereaders-service-grid-text" style="padding: 21px 10px 17px;">
                                        <h6><a href="{{ route('resources.searches', ['search' => 'supervisor grading']) }}">How do supervisors grade projects?</a></h6>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!--// Main Section \\-->

   
@endsection
