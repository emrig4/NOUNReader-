@extends('layouts.public', ['title' => 'Browse Fields'])

@push('meta')
    <meta name="description" content="Access thousands of project topics and materials, research works, academic documents, journals, theses, and books on ReadProjectTopics.com. Publish your research and earn through our premium program."/>
    
    <meta property="title" content="ReadProjectTopics.com – Project Topics, Research Materials, Academic Documents">
    
    <meta name="keywords" content="project topics and materials, final year project topics, read project materials, read projects, download complete project materials, project and materials, research materials, academic materials, project topics for university students, Nigerian project topics, free project topics, complete project materials PDF">

    <meta property="og:title" content="ReadProjectTopics.com – Project Topics and Research Materials">
    <meta property="og:description" content="Discover project topics, academic materials, theses, journals, and research documents. Publish your works and get recognition on ReadProjectTopics.com.">
    <meta property="og:url" content="https://projectandmaterials.com/">
@endpush

@push('css')
@endpush

@section('content')

<!--// Main Section \\-->
<div class="ereaders-main-section ereaders-counterfull">
    <div class="container" style="width: 100%">

        <div class="row">

            <div class="col-md-12">
                <div class="ereaders-fancy-title">
                    <h2>Quick Guides</h2>
                    <div class="clearfix"></div>
                    <p>
                        Access reliable academic resources including final year projects, thesis and  dissertations.
                    </p>
                </div>
            </div>

            <!-- LEFT SIDE – CATEGORY -->
            <aside class="col-md-4">
                <div class="ereaders-faq-tabs">
                    <h2 class="ereaders-widget-title">CATEGORY</h2>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation">
                            <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Students/Researchers</a>
                        </li>
                        <li role="presentation">
                            <a href="#subscription" aria-controls="subscription" role="tab" data-toggle="tab">Authors/Publishers</a>
                        </li>
                    </ul>
                </div>
            </aside>

            <!-- RIGHT SIDE – CONTENT -->
            <aside class="col-md-8">
                <div class="tab-content">

                    <!-- STUDENTS / RESEARCHERS -->
                    <div role="tabpanel" class="tab-pane active" id="home">

                        <h4>STUDENTS / RESEARCHERS</h4>

                        <ul>
                            <li>
                                <a href="https://projectandmaterials.com/login">Login</a> or 
                                <a href="https://projectandmaterials.com/register">Register</a> on projectandmaterials
                            </li>

                            <li>
                                Read and download unlimited 
                                <a href="https://projectandmaterials.com/project-topics-materials">research materials</a> such as 
                                <a href="https://projectandmaterials.com/resources/types/project">projects</a>, 
                                <a href="https://projectandmaterials.com/resources/types/journal">thesis</a>, 
                                 <a href="https://projectandmaterials.com/resources/types/thesis">Dissertations</a>, 
                            </li>

                            <li>
                                To access more Project materials, buy credit units.  
                                <a href="https://projectandmaterials.com/pricings">View credit plans.</a>
                            </li>
                        </ul>
<br>
                        <h4>QUICK SEARCH – HOW TO GET RESULTS</h4>

                        <ul>
                            <li>Scroll bellow  to the Search Project
tittle.</li>
                            <li>
                                Enter your keyword (e.g., crisis, productivity, corruption, advertising).
                            </li>
                          
                            <li>Click the search button.</li>
                            <li>Review the search results.</li>
                            <li>Access and download your preferred material.</li>
                        </ul>

                    </div>

                    <!-- AUTHORS / PUBLISHERS -->
                    <div role="tabpanel" class="tab-pane" id="subscription">

                        <h4>AUTHORS / PUBLISHERS</h4>

                        <ul>
                            <li>
                                <a href="https://projects.projectandmaterials.com/login">Login</a> or 
                                <a href="https://projects.projectandmaterials.com/register">Register</a> as an author.
                            </li>

                            <li>Click the Upload icon.</li>
                            <li>Select your eBook (PDF or MS Word only).</li>
                            <li>Upload and enter the required details.</li>
                            <li>Specify preview pages (optional).</li>
                            <li>Leave preview blank for full-read access.</li>
                            <li>Click Publish.</li>
                            <li>
                                <a href="https://projectandmaterials.com/faq">View FAQs for more guidance</a>
                            </li>
                        </ul>

                    </div>

                </div>
            </aside>

        </div>

    </div>
</div>



@endsection

