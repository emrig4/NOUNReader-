<!--// CopyRight \\-->
<div class="ereaders-copyright">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p><i class="fa fa-copyright"></i> 2026, All Right Reserved {{ setting('default_currency') }}<a href="https://projectandmaterials.com/">readprojecttopics</a></p>


                <ul class="footer-social-network">
                    <li><a href="https://en-gb.facebook.com/login/" class="fa fa-facebook"></a></li>
                    <li><a href="https://www.linkedin.com/uas/login" class="fa fa-linkedin"></a></li>
                    <li><a href="https://twitter.com/login?lang=en" class="fa fa-twitter"></a></li>
                    <li><a href="https://en-gb.facebook.com/login/" data-toggle="modal" data-target="#preferenceModal" class="fa fa-language"></a></li>
                </ul>

                 <ul class="flex footer-social-network"  style="margin-left: 20px; list-style: none;">
                    <li> <a  style="width: 50px;" href="{{ route('pages.faq') }}">Faq</a></li>
                    <li> <a style="width: 50px;" href="{{ route('pages.privacy') }}">Privacy</a></li>
                    <li><a style="width: 50px;" href="{{ route('pages.about') }}">About</a></li>
                </ul> 
            </div>
        </div>
    </div>
</div>
<!--// CopyRight \\-->

<!-- settings Modal: modalPoll -->
<div class="modal fade right" id="preferenceModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="false">
    @include('partials.preference_modal')
</div>
<!-- Modal: modalPoll -->


