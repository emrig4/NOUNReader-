<!--// Footer \\-->

<footer id="ereaders-footer" class="ereaders-footer-one"> 
   <div class="col-md-12">
                    <div class="ereaders-fancy-title">
                        <h2>Search Database</h2>
                        <div class="clearfix"></div>
                        <p>For accurate search results, enter the course code without spaces (e.g., ACC419). Select the material type — POP, E-Exam, or Summary — then click Search to view matching results. The search feature is designed to quickly retrieve relevant course materials when the complete course code is entered correctly. </p>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9522930547476630"
     crossorigin="anonymous"></script>
                </div>
@php
    // Get types for search dropdown
    $resourceTypes = \App\Modules\Resource\Models\ResourceType::orderBy('title')->get();
@endphp

<!-- Search Function Component -->
<div class="advanced-search-container mb-4">
    <div class="search-container">
        <!-- Type Dropdown -->
        <select class="search-select" id="searchType">
            <option value="">All Types</option>
            @foreach($resourceTypes as $type)
                <option value="{{ $type->slug }}">{{ $type->title }}</option>
            @endforeach
        </select>
        
        <input type="text" 
               class="search-input" 
               id="searchQuery" 
               placeholder="SEARCH COURSE CODE E.G ACC419"
               autocomplete="off">
        
        
        <button class="search-btn" id="searchBtn">SEARCH</button>
    </div>
</div>
 

<!-- Search Function Styles -->
<style>
.advanced-search-container {
    margin-bottom: 30px;
}

.search-container {
    display: flex;
    background-color: #eee;
    padding: 10px;
    gap: 5px;
    align-items: stretch;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    flex-wrap: wrap;
}

.search-input {
    flex: 1 1 200px;
    border: none;
    padding: 0 15px;
    font-size: 14px;
    color: #666;
    outline: none;
    background-color: white;
    border-radius: 4px;
    min-width: 150px;
}

.search-select {
    flex: 0 0 auto;
    min-width: 160px;
    max-width: 200px;
    border: none;
    border-left: 1px solid #ddd;
    padding: 0 10px;
    font-size: 13px;
    color: #666;
    outline: none;
    background-color: white;
    cursor: pointer;
    border-radius: 4px;
}

.search-select:first-of-type {
    border-left: none;
}

.search-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 0 25px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    text-transform: uppercase;
    transition: background 0.3s;
    border-radius: 4px;
    min-width: 100px;
}

.search-btn:hover {
    background-color: #218838;
}

.search-btn:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .search-container {
        flex-direction: column;
        height: auto;
        gap: 8px;
        padding: 15px;
    }
    
    .search-input,
    .search-select,
    .search-btn {
        width: 100%;
        border-radius: 4px;
        border-left: none;
        max-width: 100%;
        flex: 1 1 100%;
    }
    
    .search-select:first-of-type {
        border-radius: 4px;
    }
    
    .search-btn {
        padding: 12px;
    }
}
</style>

<!-- Search Function JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.getElementById('searchBtn');
    const searchQuery = document.getElementById('searchQuery');
    const searchType = document.getElementById('searchType');
    
    if (searchBtn) {
        searchBtn.addEventListener('click', function() {
            performSearch();
        });
    }
    
    // Allow search on Enter key
    if (searchQuery) {
        searchQuery.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    }
    
    function performSearch() {
        const query = searchQuery ? searchQuery.value.trim() : '';
        const type = searchType ? searchType.value : '';
        
        // Build search URL with parameters
        const searchParams = new URLSearchParams();
        if (query) searchParams.append('search', query);
        if (type) searchParams.append('type', type);
        
        // Redirect to search page with parameters
        const searchUrl = `/resources/search?${searchParams.toString()}`;
        window.location.href = searchUrl;
    }
});
</script>
<!-- COUNTERS -->
<div class="col-md-12">
    <div class="ereaders-counter ereaders-about-counter">
        <ul>

            <li>
                <div class="ereaders-counter-text">
                    <i class="icon ereaders-document"></i>
                    <div class="ereaders-scroller">
                        <h2 class="numscroller" data-min="0" data-max="25124" data-delay="10" data-increment="100">27045</h2> 
                        <span>POP EXAM</span>
                    </div>
                </div>
            </li>

            <li>
                <div class="ereaders-counter-text">
                    <i class="icon ereaders-book"></i>
                    <div class="ereaders-scroller">
                        <h2 class="numscroller" data-min="0" data-max="14510" data-delay="10" data-increment="100">14510</h2> 
                        <span>TMA</span>
                    </div>
                </div>
            </li>

            <li>
                <div class="ereaders-counter-text">
                    <i class="icon ereaders-download-content"></i>
                    <div class="ereaders-scroller">
                        <h2 class="numscroller" data-min="0" data-max="13550" data-delay="10" data-increment="100">13550</h2> 
                        <span>E-EXAM</span>
                    </div>
                </div>
            </li>

            <li>
                <div class="ereaders-counter-text">
                    <i class="icon ereaders-document"></i>
                    <div class="ereaders-scroller">
                        <h2 class="numscroller" data-min="0" data-max="11932" data-delay="10" data-increment="100">11932</h2> 
                        <span>SUMMARIES</span>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</div>


    <!--// Footer Widget \\-->
    <div class="ereaders-footer-widget">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="ereaders-footer-newslatter flex flex-col items-center">
                        <h2>Subscribe to Our Newsletter</h2>
                        <p>Sign up to our newsletter to be the first to receive the latest project topics, research materials, and academic tips on ReadProjectTopics.</p>
                        <form action="#">
                            <input value="Enter Your Email Address" 
                                onblur="if(this.value == '') { this.value ='Enter Your Email Address'; }" 
                                onfocus="if(this.value =='Enter Your Email Address') { this.value = ''; }" 
                                tabindex="0" 
                                type="email">
                            <input type="submit" value="Subscribe">
                        </form>
                    </div>
                </div>
            </div>

            <div class="row p-2">
                <div class="col-md-4 pt-5">
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">Projects</h4>
                        <ul class="footer-menu list-style">
                            <li><a href="https://projectandmaterials.com/project-topics-materials">Browse Topics</a></li>
                            <li><a href="https://projectandmaterials.com/resources/fields/project-topics">Departments</a></li>
                            <li><a href="https://projectandmaterials.com/search-topic">Search Topics</a></li>
                            <li><a href="https://projectandmaterials.com/resources/submit">Publish projects</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-8 pt-5 flex justify-between">
                    
                    <div>
                        <div class="footer-widget">
                            <h4 class="footer-widget-title">Company</h4>
                            <ul class="footer-menu list-style">
                                <li><a href="https://projectandmaterials.com/about-us">About</a></li>
                                <li><a href="https://projectandmaterials.com/how-it-works">How It Works</a></li>
                                <li><a href="https://projectandmaterials.com/faq">FAQ</a></li>
                                <li><a href="https://projectandmaterials.com/pricings">Pricing</a></li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <div class="footer-widget">
                            <h4 class="footer-widget-title">Quick Links</h4>
                            <ul class="footer-menu list-style">
                                <li><a href="https://projectandmaterials.com/">Home</a></li>
                                <li><a href="https://projectandmaterials.com/register">Join</a></li>
                                <li><a href="https://projectandmaterials.com/login">Login</a> 
                               |  <form method="POST" action="{{ route('logout') }}" style="margin: 0; padding: 0;">
                            @csrf
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" style="display: block; padding: 0; background: none; border: none; cursor: pointer; text-decoration: none;">
                                Logout
                            </a></li>
                        </form></li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <div class="footer-widget">
                            <h4 class="footer-widget-title">Tools</h4>
                            <ul class="footer-menu list-style">
                                <li><a href="https://projectandmaterials.com/blog">Blog</a></li>
                                <li><a href="https://projectandmaterials.com/plagiarism-checker">Plagiarism Checker</a></li>
                                <li><a href="#">Paraphraser</a></li>
                                <li><a href="#">Re-writer</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!--// Footer Widget \\-->

    @include('partials.copyright')
</footer>
<!--// Footer \\-->