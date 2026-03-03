@extends('layouts.public', ['title' => 'Author | ' . $author->fullname ])

@push('css')
@endpush


@section('content')
        <!--// SubHeader \\-->
    <div class="ereaders-subheader">
        <div class="ereaders-subheader-text py-10">
            <span class="ereaders-subheader-transparent"></span>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 style="text-align: center">Author Detail</h1>
                    </div>
                </div>
            </div>
        </div>
      
    </div>
    <!--// SubHeader \\-->

    <!--// Main Content \\-->
    <div class="ereaders-main-content">

        <!--// Main Section \\-->
        <div class="ereaders-main-section ereaders-author-detailfull">
            <div class="container">
                <div class="row">

                    <aside class="col-md-4">

                        <!--// Widget author-info \\-->
                        <div class="widget widget_author_info widget_border">
                            <figure>
                                <a href="#"><img src="{{ $author->user->profile_photo_url }}"></a>
                            </figure>
                            <div class="widget-author-info-text">
                                <h5><a href="author-detail.html">{{ $author->fullname }}</a></h5>
                                <span>Publisher of <small>{{ $author->user->account->resources()->count() }}+ books</small></span>
                                <p>
                                    {!! mb_strimwidth($author->user->account->biography, 0, 60, "...") !!}
                                </p>
                                <div class="skillst">
                                    <!-- <h6>Activity Level <span>80%</span></h6> -->
                                    <div class="progressbar1" data-width="100" data-target="100"></div>
                                </div>
                                <div class="ereaders-blog-social">
                                    <ul>
                                        <li><a href="{{ $author->user->account->facebook }}" class="fa fa-facebook"></a></li>
                                        <li><a href="{{ $author->user->account->twitter }}" class="fa fa-twitter"></a></li>
                                        <li><a href="{{ $author->user->account->youtube }}" class="fa fa-pinterest-p"></a></li>
                                        <li><a href="{{ $author->user->account->youtube }}" class="fa fa-google-plus"></a></li>
                                    </ul>
                                </div>
                                <div>
                                    @if( auth()->user() )
                                        @if(is_follow($author->user->id))
                                             <a href="{{ route('account.unfollow', $author->user->id) }}" class="btn btn-secondary bg-gray-400 text-white">Unfollow<i class="icon mx-2 text-xs text-white">| {{ $author->user->followers->count()  }}</i></a>
                                        @else
                                            <a href="{{ route('account.follow', $author->user->id) }}" class="btn btn-primary">Follow<i class="icon mx-2 text-xs text-white">| {{ $author->user->followers->count()  }}</i></a>
                                           
                                        @endif
                                    @endif

                                </div>
                            </div>
                        </div>
                        <!--// Widget author-info \\-->

                        <!--// Widget Contact \\-->
                        <!-- <div class="widget widget_contact_form widget_border">
                            <h2 class="ereaders-widget-title">Contact Form</h2>
                            <form>
                                <ul>
                                    <li>
                                        <label>Name:</label>
                                        <input value="Enter Your Name" onblur="if(this.value == '') { this.value ='Enter Your Name'; }" onfocus="if(this.value =='Enter Your Name') { this.value = ''; }" tabindex="0" type="text">
                                        <i class="fa fa-user"></i>
                                    </li>
                                    <li>
                                        <label>Email:</label>
                                        <input value="Enter Your Email" onblur="if(this.value == '') { this.value ='Enter Your Email'; }" onfocus="if(this.value =='Enter Your Email') { this.value = ''; }" tabindex="0" type="email">
                                        <i class="fa fa-envelope"></i>
                                    </li>
                                    <li>
                                        <label>Phone:</label>
                                        <input value="Enter Your Email" onblur="if(this.value == '') { this.value ='Enter Your Email'; }" onfocus="if(this.value =='Enter Your Email') { this.value = ''; }" tabindex="0" type="text">
                                        <i class="fa fa-phone"></i>
                                    </li>
                                    <li>
                                        <label>Message</label>
                                        <textarea placeholder="Type Your Message"></textarea>
                                        <i class="fa fa-commenting"></i>
                                    </li>
                                    <li><input type="submit" value="Submit Now" class="submit"></li>
                                </ul>
                            </form>
                        </div> -->
                        <!--// Widget Contact \\-->

                    </aside>

                    <div class="col-md-8">
                        <h2 class="ereaders-section-heading">Author Overview</h2>
                        <div class="ereaders-rich-editor">
                           {{ $author->user->account->biography }}
                        </div>
                       <!--  <h2 class="ereaders-section-heading">About Education</h2>
                        <div class="ereaders-about-education">
                            <ul>
                                <li>
                                    <span></span>
                                    <h6>O Level <span>1978-1980 Global Universities </span></h6>
                                    <p>Ut porta massa in risus maximus, eget sodales massa malesuada. Fusce et neque aliquet, mollis tellus ut, rutrum nunc. Cras congue magna et velit accumsan tempor. Etiam tempor nisi in tortor sagittis pharetra. Phasellus ut pulvinar risus. Nulla odio ipsum, sagittis at libero sed, sollicitudin euismod est.</p>
                                </li>
                                <li>
                                    <span></span>
                                    <h6>Master in Fine Arts <span>1982-1985 Harvard University </span></h6>
                                    <p>Morbi condimentum, ex ac aliquam congue, sapien eros commodo dolor, eu semper mauris arcu non mauris. Aliquam erat volutpat. Phasellus non nisi ligula. Phasellus accumsan nunc vitae enim interdum fringilla. Integer vel elementum diam. Vestibulum tincidunt, tortor id tristique molestie, ipsum nibh rutrum enim</p>
                                </li>
                                <li>
                                    <span></span>
                                    <h6>Degree in Journlist <span>1990-1994 Oxford Universities </span></h6>
                                    <p>Suspendisse potenti. Sed a mi ac magna fringilla faucibus. Vestibulum sed libero orci. Curabitur consectetur, augue eu vehicula elementum, dolor neque pretium mauris, vel porttitor enim purus nec magna. Sed purus ex, feugiat sit amet placerat condimentum, tempus vitae ipsum. Nulla magna nulla, suscipit id lacinia et.</p>
                                </li>
                            </ul>
                        </div>
                        <h2 class="ereaders-section-heading">Award of Performance</h2>
                        <div class="ereaders-about-education">
                            <ul>
                                <li>
                                    <span></span>
                                    <h6>Winner, Indie Award for Non-Fiction, <span>2016</span></h6>
                                    <p>Cras faucibus, nunc vitae venenatis porttitor, augue lacus porttitor justo, vitae lobortis leo diam et massa. Sed quis tellus vitae neque ultrices faucibus nec et velit. Aliquam erat volutpat. Phasellus fringilla nulla at vulputate feugiat.</p>
                                </li>
                                <li>
                                    <span></span>
                                    <h6>Shortlisted, Nielsen BookData Booksellers Choice Award, <span>2016</span></h6>
                                    <p>Nulla magna nulla, suscipit id lacinia et, lacinia eget sem. Cras ut elit et nisi ultricies faucibus et ac sem. Fusce ac luctus arcu. Quisque odio risus, condimentum sit amet ornare eu, congue non diam. Aliquam pretium nibh urna, eget fermentum nunc varius nec.</p>
                                </li>
                                <li>
                                    <span></span>
                                    <h6>Longlisted, Biography Book of the Year, <span>2016</span></h6>
                                    <p>Integer vel elementum diam. Vestibulum tincidunt, tortor id tristique molestie, ipsum nibh rutrum enim, id elementum nulla diam vitae massa. Donec vitae posuere ante. Suspendisse efficitur mauris at sagittis volutpat. Etiam ac ultricies tellus, ac lacinia leo.</p>
                                </li>
                            </ul>
                        </div> -->
                    </div>
                    
                </div>
            </div>
        </div>
        <!--// Main Section \\-->

        <!--// Main Section \\-->
        <div class="ereaders-main-section ereaders-book-gridfull">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="ereaders-fancy-title">
                            <h2>Related Books From Author</h2>
                            <div class="clearfix"></div>
                            <p>Large online bookstores offer used books for sale, too. Individuals wishing to sell their used Books</p>
                        </div>
                        <div class="ereaders-books ereaders-book-grid">
                            <ul class="row">
                                @foreach($author->user->account->resources as $resource)
                                <li class="col-md-3">
                                   
                                    <div class="ereaders-book-grid-text">
                                        <h2><a href="book-detail.html">{{ $resource->title }}</a></h2>
                                        <a href="{{ route('resources.show', $resource->slug) }}" class="ereaders-simple-btn ereaders-bgcolor">Download</a>
                                    </div>
                                </li>
                                @endforeach
                               
                               
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--// Main Section \\-->
    </div>
    <!--// Main Content \\-->
@endsection

@push('js')
@endpush

