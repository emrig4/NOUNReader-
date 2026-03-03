<nav id="menu" class="menu navbar navbar-default">
    <ul class="level-1 navbar-nav">
        <li class="active"><a href="/">Home</a></li>
            <li><a href="https://pamdev.online/resources/fields/noun-resources">Resources</a></li>
            

         <li><a href="#">Download</a><span class="has-subnav"><i class="fa fa-angle-down"></i></span>
            <ul class="sub-menu level-2">
                <li><a href="https://pamdev.online/resources/topics/100-level-course-summaries">COURSE SUMMARIES</a></li>
              <li><a href="https://pamdev.online/resources/topics/noun-pop-past-questions">POP EXAMS</a></li>
              <li><a href="https://pamdev.online/resources/topics/noun-tma-past-questions-answers">TMA</a></li>
              <li><a href="https://pamdev.online/resources/topics/noun-e-exam-past-questions">E-EXAMS</a></li>
              <li><a href="http://projectandmaterials.com/">PROJECTS</a></li>

            </ul>
        </li>
        
     <li><a href="">TUTORIAL</a><span class="has-subnav"><i class="fa fa-angle-down"></i></span>
            <ul class="sub-menu level-2">
                 <li><a href="https://www.youtube.com/@NOUNTUTORIAL">YOUTUBE CHANNEL</a></li>
          
            </ul>
        </li>
        
        <li><a href="#">SERVICES</a><span class="has-subnav"><i class="fa fa-angle-down"></i></span>
            <ul class="sub-menu level-2">
                <li><a href="https://api.whatsapp.com/send/?phone=2349038349959&text=Hi%21+I+need+to+hire+a+professional+writer+for+my+project.&type=phone_number&app_absent=0">Project writing</a></li>
                <li><a href="https://projectandmaterials.com/plagiarism-checker">Plagiarism checker</a></li>
                <li><a href="https://projectandmaterials.com/ai-detector">AI Detector</a></li>
                <li><a href="https://www.youtube.com/@project-and-materials">Youtube Tutorials</a></li>
                  <!--//<li><a href="https://projectandmaterials.com/topics-suggestion">Topic suggestion</a></li>\\-->
            </ul>
        </li>
        
        <li>
            @if(auth()->user())
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                   <img class="h-10 ml-5 rounded-full object-cover user-avatar" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                   <span class="has-subnav"><i class="fa fa-angle-down"></i></span>
                @else
                    <span class="inline-flex rounded-md user-name">
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
                   
                    <li><a href="https://projectandmaterials.com/account/subscription">Wallet</a></li>
                    <li><a href="{{ route('account.followings') }}">Followings</a></li>
                    <li><a href="{{ route('account.notifications') }}">Notifications</a></li>
                    <li><a href="https://projectandmaterials.com/account/favorites">favorites</a></li>
                    <li><a href="{{ route('account.settings') }}">Settings</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               style="cursor: pointer;">
                                Logout
                            </a>
                        </form>
                    </li>
                </ul>
            @else
                <a class="ml-5" href="{{ route('login') }}">Login</a><span class="has-subnav"><i class="fa fa-angle-down"></i></span>
                <ul class="sub-menu level-2">
                    <li><a class="" href="{{ route('login') }}">Login</a></li>
                    <li><a class="" href="{{ route('register') }}">Register</a></li>
                </ul>
            @endif
        </li>
    </ul>
</nav>