<!-- Footer with logout fix -->
<footer id="ereaders-footer" class="ereaders-footer-one">    
    <div class="ereaders-footer-widget">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ereaders-footer-strip">
                        <div class="ereaders-footer-strip-innner">
                            <p>© 2022 <a href="index.html">readprojecttopics</a> All rights reserved.</p>
                            <div class="ereaders-footer-strip-right">
                                <ul>
                                    @auth
                                        <li>
                                            <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-link" style="background: none; border: none; padding: 0; text-decoration: none; color: inherit; cursor: pointer;">
                                                    <a style="text-decoration: none;">Logout</a>
                                                </button>
                                            </form>
                                        </li>
                                    @else
                                        <li><a href="{{ url('/login') }}">Login</a></li>
                                        <li><a href="{{ url('/register') }}">Register</a></li>
                                    @endauth
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

