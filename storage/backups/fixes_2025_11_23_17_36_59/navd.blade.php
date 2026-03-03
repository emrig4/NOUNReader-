<!--// Navigation \\-->
<a href="#menu" class="menu-link active"><span></span></a>
<nav id="menu" class="menu navbar navbar-expand-lg">
    <ul class="level-1 navbar-nav">
        <li class="active">
            <a href="#">Resources</a><span class="has-subnav"><i class="fa fa-angle-down"></i></span>
            <ul class="sub-menu level-2">
                <li><a href="{{ route('resources.topics') }}">Browse Topics</a>
                </li>
                <li><a href="{{ route('resources.fields') }}">Browse Fields</a>
                </li>
                <li><a href="#">Blog</a>
                </li>
                </li>
                 <li><a href="#">Guides</a>
                </li>
            </ul>
        </li>
        <li><a href="{{ route('pricings.index') }}">Pricing</a>
        </li>
        
        
        <li><a href="#">Contact Us</a><span class="has-subnav"><i class="fa fa-angle-down"></i></span>
            <ul class="sub-menu level-2">
                <li><a href="a#">Support</a></li>
            </ul>
        </li>
        <li>
            @if(auth()->user())
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                   <img class="h-10 mr-5 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                   <span class="has-subnav"><i class="fa fa-angle-down"></i></span>
                @else
                    <span class="inline-flex rounded-md">
                       {{ Auth::user()->name }}
                        <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        <span class="has-subnav"><i class="fa fa-angle-down"></i></span>
                    </span>
                @endif
                <span class="has-subnav"><i class="fa fa-angle-down"></i></span>
                <ul class="sub-menu level-2">
                    <li><a href="{{ route('account.index') }}">Dashboard</a></li>
                    <li><a href="">Profile</a></li>
                     <li><a href="">Notifications</a></li>
                    <li><a href="{{ route('account.settings') }}">Settings</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer; text-decoration: underline; padding: 0; margin: 0;">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            @else
            <a class="mr-5" href="{{ route('login') }}">Login</a><span class="has-subnav"><i class="fa fa-angle-down"></i></span>
                <ul class="sub-menu level-2">
                    <li class=""><a class="" href="{{ route('register') }}">Register</a></li>
                </ul>
            @endif
           
        </li>

    </ul>
</nav>
<!--// Navigation \\-->
