<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Topics Suggestion Tool - EOE2.COM</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/topics-suggestion/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header-section">
            <h1 class="main-title">Research Topics Suggestion Tool</h1>
            <p class="subtitle">Get project topics, thesis topics, dissertation topics in your field of study</p>
        </div>

        <hr class="divider">

        <!-- Form Section -->
        <div class="form-section">
            <div class="instruction-text">
                <p>To use this Topics Suggestion Tool, enter your field of study and select type, then click Get Topics</p>
            </div>

            <form id="topicsForm" class="topics-form">
                <div class="form-row">
                    <div class="form-group">
                        <select id="department" name="department" required>
                            <option value="">Enter Field / Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>

                    <div class="form-group">
                        <select id="type" name="type" required>
                            <option value="">Select type of work</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <button type="submit" class="get-topics-btn">
                    <i class="fas fa-search"></i>
                    Get Topics
                </button>
            </form>
        </div>

        <!-- Loading Section -->
        <div id="loadingSection" class="loading-section" style="display: none;">
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Finding the best research topics for you...</p>
            </div>
        </div>

        <!-- Results Section -->
        <div id="resultsSection" class="results-section" style="display: none;">
            <div class="results-header">
                <h2 id="resultsTitle">Suggested Research Topics</h2>
                <div class="results-actions">
                    <button id="exportBtn" class="export-btn" style="display: none;">
                        <i class="fas fa-download"></i>
                        Export Results
                    </button>
                    <button id="newSearchBtn" class="new-search-btn">
                        <i class="fas fa-search"></i>
                        New Search
                    </button>
                </div>
            </div>

            <div id="searchCriteria" class="search-criteria">
                <p id="searchInfo"></p>
            </div>

            <div id="topicsList" class="topics-list">
                <!-- Topics will be populated here -->
            </div>

            <div id="noResults" class="no-results" style="display: none;">
                <i class="fas fa-search"></i>
                <h3>No topics found</h3>
                <p>Try selecting a different department or work type.</p>
            </div>
        </div>

        <!-- Popular Fields Section -->
        <div class="popular-fields-section">
            <h2 class="popular-fields-title">Some Popular Fields</h2>
            <div class="popular-fields-grid">
                @foreach($departments as $index => $department)
                    @if($index < 30) {{-- Show first 30 departments --}}
                        <div class="popular-field-item" data-department="{{ $department }}">
                            <a href="#" class="popular-field-link">{{ $department }} Project Topics</a>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Help Section -->
        <div class="help-section">
            <div class="help-content">
                <h3>What is a Research Topic?</h3>
                <p>A research topic is a subject or issue that a student / researcher is interested in when conducting research. A research topic is a subject that a research project sets out to explore or discuss in details.</p>

                <h3>How to Choose a Good Research Topic</h3>
                <p>Choosing a research / project topic is one of the most difficult part of research process. Your choice of topic is the first step to writing an excellent project, dissertation or thesis. When trying to get a project topic or topic for your thesis consider the following:</p>

                <ul>
                    
                    <li><strong>Interest:</strong> It's important you choose an area or an idea that holds significant interest for you. This way even when you encounter challenges in the course of conducting and writing your dissertation, thesis etc you will likely not be thrown off. Research Projects require some tenacity and it helps that you work on something you find interesting.</li>

                    <li><strong>Relevance:</strong> How relevant is the idea you wish to work on. The chances are, the more relevant your project topic is the more likely you will put in the work to conclude it.</li>

                    <li><strong>Availability of Research or Relevant Data:</strong> If you are to choose or develop a research topic you should make sure it's on something that has verifiable or reliable data or at least some historical data to glean from. The more information available for your topic idea the better/easier for you.</li>
                </ul>

                <h3>Tips to Generating a Good Research Topic</h3>
                <ul>
                    <li><strong>Look for trending ideas</strong> - If you are studying for a health related degree for instance the Covid-19 pandemic is a very topical and highly current issue that you can focus on to develop a research topic. Look around your field and note the current issues. Most times they give you enough background to base/develop your research idea/topic.</li>

                    <li><strong>Read</strong> - reading a lot of materials in your field is a sure way to spring up suitable ideas in your academic field. Read quality books, magazines, websites in your field and make notes of striking information. You will easily realise you have several ideas to develop your research.</li>

                    <li><strong>Ask Questions</strong> - Talk to practitioners, professionals in your field and discover issues, pain points etc that can form the basis for innovative and problem solving research ideas/topics.</li>

                    <li><strong>Look at previous topics</strong> from other researchers in your field - Take advantage of your school library and especially your departmental library to find past projects in your field and area of interest. This should serve as a guide in helping you to frame your own topic, with an extra sense of assurance that you have reference materials if the topic is approved. However, avoid overused topics.</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/topics-suggestion/script.js') }}"></script>
</body>
</html>