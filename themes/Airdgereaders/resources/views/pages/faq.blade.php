@extends('layouts.public', ['title' => 'Browse Fields'])
@push('meta')
    <meta name="description" content="Publish your research works on the largest academia library, gain recognigtion and also earn through our premium program."/>
    <meta name="keywords" content="Authoran, Digital Academic Resources, Research, Project, Thesis">
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
                    <h2>Frequently Ask Question</h2>
                    <div class="clearfix"></div>
                    <p>Browse through our frequently asked questions</p>
                </div>
            </div>

            <aside class="col-md-4">
                <div class="ereaders-faq-tabs">
                    <h2 class="ereaders-widget-title">Category</h2>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class=""><a href="#home" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="false">General</a></li>
                        <li role="presentation" class=""><a href="#subscription" aria-controls="subscription" role="tab" data-toggle="tab" aria-expanded="false">Subscription</a></li>
                    </ul>
                </div>
            </aside>

            <aside class="col-md-8">
                <!-- Tab panes -->
                <div class="tab-content">


                    <div role="tabpanel" class="tab-pane active" id="home">
                        <div class="panel-group ereaders-faq-accordion" id="accordiontwo" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordiontwo" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            What is Nounreader?

                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                        <p>Nounreader is an independent educational platform designed to help students access organised academic resources such as TMA, POP past questions, course summaries, and exam preparation materials in a simple and distraction-free environment.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordiontwo" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            What materials are available on Nounreader?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <p>Nounreader provides access to course summaries, POP past questions, Tutor Marked Assignments (TMA), and exam preparation materials to support effective study and better understanding of your courses.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordiontwo" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                           Is Nounreader free to use?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                    <div class="panel-body">
                                        <p>Some features may be available for free, but most resources require credits to unlock and access.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordiontwo" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            Is Nounreader affiliated with NOUN?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                    <div class="panel-body">
                                        <p>No. Nounreader is an independent platform and is not affiliated with, endorsed by, or officially connected to the National Open University of Nigeria or any of its departments.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div role="tabpanel" class="tab-pane" id="subscription">
                        <div class="panel-group ereaders-faq-accordion" id="accordionthree" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingSix">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#accordionthree" href="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                            wWhat are the benefits of buying credits?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSix" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingSix">
                                    <div class="panel-body">
                                        <p>Buying or topping up credits allows you to unlock and access important academic resources such as TMA, POP past questions, course summaries, and exam preparation materials on Nounreader.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingSeven">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionthree" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                            Do credits expire after purchase?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                                    <div class="panel-body">
                                        <p>Credits do not expire unless otherwise stated. You can use them anytime to access available resources on the platform.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingEight">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionthree" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                            Can I cancel my purchase?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
                                    <div class="panel-body">
                                        <p>Credit purchases are not subscriptions and do not renew automatically. Once purchased, credits remain in your account until used. If you experience any issue with your payment, you can contact support for assistance. </p>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="headingNine">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionthree" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                            What is Nounreader credit unit?
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingNine">
                                    <div class="panel-body">
                                        <p>Nounreader credit unit is the value added to your wallet after a successful payment. These credits are used to unlock and access resources on the platform.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </aside>

        </div>

    </div>
</div>
<!--// Main Section \\-->
@endsection
