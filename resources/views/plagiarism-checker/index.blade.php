<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Plagiarism Checker - ReadProjectTopics.com</title>
    <link rel="stylesheet" href="{{ asset('css/plagiarism-checker/style.css') }}">
</head>
<body>
    <div class="plagiarism-container">
        <!-- Header -->
        <div class="plagiarism-header">
            <h1>🔍 Plagiarism Checker</h1>
            <p>Check your academic content for originality and ensure proper citations. Free tool for students and researchers.</p>
        </div>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9522930547476630"
     crossorigin="anonymous"></script>
        <!-- Usage Limits -->
        <div class="usage-limits" id="usageLimits">
            <h3>Today's Usage</h3>
            <div class="limits-grid">
                <div class="limit-item">
                    <div class="limit-value">{{ $limits ? $limits->remaining_checks : ($user ? 20 : 5) }}</div>
                    <div class="limit-label">Checks Remaining</div>
                </div>
                <div class="limit-item">
                    <div class="limit-value">1000</div>
                    <div class="limit-label">Words Per Check</div>
                </div>
            </div>
        </div>

        @auth
            <div class="alert alert-success">
                Welcome, {{ auth()->user()->name }}! You have {{ $limits ? $limits->remaining_checks : 20 }} checks remaining today.
            </div>
        @else
            <div class="alert alert-info">
                You are using the free tier with limited checks. <a href="{{ route('login') }}">Sign in</a> for more daily checks or <a href="{{ route('register') }}">create an account</a>.
            </div>
        @endauth

        <!-- Main Content -->
        <div class="plagiarism-content">
            <!-- Input Section -->
            <div class="input-section">
                <h2>📝 Enter Your Text</h2>
                
                <form id="plagiarismForm" action="{{ route('plagiarism-checker.check') }}" method="POST">
                    @csrf
                    
                    <div class="input-group">
                        <label for="textInput">Paste or type your text below:</label>
                        <textarea 
                            id="textInput" 
                            name="text" 
                            class="text-input" 
                            placeholder="Paste your essay, article, or any academic content here... (minimum 50 words required)"
                            required
                        ></textarea>
                        <div id="wordCounter" class="word-counter">0 / 1000 words</div>
                    </div>

                   

                    <button type="submit" id="checkBtn" class="btn btn-primary" style="width: 100%;">
                        <div class="loading"><div class="spinner"></div>Check for Plagiarism</div>
                    </button>
                </form>

                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Results Section -->
            <div class="results-section" id="resultsSection">
                <h2>📊 Results</h2>
                <div class="check-status">
                    <p>Enter your text and click "Check for Plagiarism" to see results here.</p>
                    <p><small>Results will show similarity percentage and potential sources.</small></p>
                </div>
            </div>
        </div>

        <!-- Loading Section -->
        <div class="loading-section" id="loadingSection" style="display: none;">
            <!-- Loading content will be inserted here -->
        </div>

        <!-- Features Section -->
        <div class="features-section">
            <h2>✨ Features</h2>
            <div class="features-grid">
                <div class="feature-item">
                    <h3>🔍 Advanced Detection</h3>
                    <p>Our algorithm checks against millions of academic sources and web pages to identify potential plagiarism.</p>
                </div>
                <div class="feature-item">
                    <h3>📱 Mobile Friendly</h3>
                    <p>Access the plagiarism checker from any device. Works perfectly on desktop, tablet, and mobile.</p>
                </div>
                <div class="feature-item">
                    <h3>⚡ Fast Results</h3>
                    <p>Get your plagiarism report in seconds. No waiting, no delays, just accurate results.</p>
                </div>
                <div class="feature-item">
                    <h3>🔒 Secure & Private</h3>
                    <p>Your content is never stored permanently. We prioritize your privacy and data security.</p>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="how-it-works">
            <h2>🔧 How It Works</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Enter Your Text</h3>
                        <p>Paste your content or upload a document. Minimum 50 words required for accurate analysis.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>AI Analysis</h3>
                        <p>Our advanced algorithm analyzes your content against billions of web pages and academic sources.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Get Results</h3>
                        <p>Receive a detailed report with similarity percentage, matched sources, and recommendations.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Guidelines -->
        <div class="guidelines">
            <h2>📋 Usage Guidelines</h2>
            <div class="guideline-grid">
                <div class="guideline-item">
                    <h4>✅ Best Practices</h4>
                    <ul>
                        <li>Check your work before submission</li>
                        <li>Always cite your sources properly</li>
                        <li>Use paraphrasing techniques</li>
                        <li>Review flagged sections carefully</li>
                    </ul>
                </div>
                <div class="guideline-item">
                    <h4>⚠️ Important Notes</h4>
                    <ul>
                        <li>Free tier: 1,000 words per check</li>
                        <li>Daily limits: 5 checks (guests), 20 checks (users)</li>
                        <li>Results are estimates, not definitive</li>
                        <li>Consider context and proper citations</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- History Section -->
        @auth
            <div class="history-section">
                <h2>📚 Recent Checks</h2>
                <p>View your plagiarism check history and download reports.</p>
                <a href="{{ route('plagiarism-checker.history') }}" class="btn btn-secondary">View Full History</a>
            </div>
        @endauth

        <!-- Footer -->
        <div class="plagiarism-footer">
            <p>&copy; 2026 ReadProjectTopics.com - Academic Resources Platform</p>
            <p>
                <a href="#">Privacy Policy</a> | 
                <a href="#">Terms of Service</a> | 
                <a href="#">Contact Support</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('js/plagiarism-checker/script.js') }}"></script>
    
    <style>
        .features-section,
        .how-it-works,
        .guidelines {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            margin: 2rem 0;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            border: 1px solid #E5E7EB;
        }

        .features-section h2,
        .how-it-works h2,
        .guidelines h2 {
            color: #374151;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .feature-item h3 {
            color: #23A455;
            margin-bottom: 0.75rem;
            font-size: 1.125rem;
        }

        .feature-item p {
            color: #6B7280;
            line-height: 1.6;
        }

        .steps {
            display: grid;
            gap: 1.5rem;
        }

        .step {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .step-number {
            background: #23A455;
            color: white;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .step-content h3 {
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 1.125rem;
        }

        .step-content p {
            color: #6B7280;
            line-height: 1.6;
        }

        .guideline-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .guideline-item h4 {
            color: #374151;
            margin-bottom: 1rem;
            font-size: 1.125rem;
        }

        .guideline-item ul {
            list-style: none;
            padding: 0;
        }

        .guideline-item li {
            color: #6B7280;
            padding: 0.5rem 0;
            padding-left: 1.5rem;
            position: relative;
        }

        .guideline-item li:before {
            content: "•";
            color: #23A455;
            position: absolute;
            left: 0;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .features-grid,
            .guideline-grid {
                grid-template-columns: 1fr;
            }
            
            .step {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</body>
</html>
