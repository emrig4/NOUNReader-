<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Content Detector - ReadProjectTopics.com</title>
    <link rel="stylesheet" href="{{ asset('css/ai-detector/style.css') }}">
</head>
<body>
    <div class="ai-detector-container">
        <!-- Header -->
        <div class="ai-detector-header">
            <h1>🤖 AI Content Detector</h1>
            <p>Detect whether your content was written by AI or humans. Advanced analysis for students, educators, and content creators.</p>
        </div>

        <!-- Usage Limits -->
        <div class="usage-limits" id="usageLimits">
            <h3>Today's Usage</h3>
            <div class="limits-grid">
                <div class="limit-item">
                    <div class="limit-value">{{ $limits ? $limits->remaining_checks : ($user ? 30 : 10) }}</div>
                    <div class="limit-label">Checks Remaining</div>
                </div>
                <div class="limit-item">
                    <div class="limit-value">1500</div>
                    <div class="limit-label">Words Per Check</div>
                </div>
            </div>
        </div>

        @auth
            <div class="alert alert-success">
                Welcome, {{ auth()->user()->name }}! You have {{ $limits ? $limits->remaining_checks : 30 }} checks remaining today.
            </div>
        @else
            <div class="alert alert-info">
                You are using the free tier with limited checks. <a href="{{ route('login') }}">Sign in</a> for more daily checks or <a href="{{ route('register') }}">create an account</a>.
            </div>
        @endauth

        <!-- Main Content -->
        <div class="ai-detector-content">
            <!-- Input Section -->
            <div class="input-section">
                <h2>📝 Enter Your Text</h2>
                
                <form id="aiDetectorForm" action="{{ route('ai-detector.check') }}" method="POST">
                    @csrf
                    
                    <div class="input-group">
                        <label for="textInput">Paste or type your text below:</label>
                        <textarea 
                            id="textInput" 
                            name="text" 
                            class="text-input" 
                            placeholder="Paste your essay, article, or any content here to check if it was written by AI or humans... (minimum 50 words required)"
                            required
                        ></textarea>
                        <div id="wordCounter" class="word-counter">0 / 1500 words</div>
                    </div>

                    <div class="input-group">
                        <label>Or upload a file:</label>
                        <div class="file-upload-area">
                            <input type="file" id="fileUpload" name="file" accept=".txt,.pdf,.doc,.docx">
                            <div class="file-upload-text">📁 Click to upload or drag & drop</div>
                            <div class="file-upload-hint">Supports TXT, PDF, DOC, DOCX files (max 10MB)</div>
                        </div>
                    </div>

                    <button type="submit" id="checkBtn" class="btn btn-primary" style="width: 100%;">
                        <div class="loading"><div class="spinner"></div>Detect AI Content</div>
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
                    <p>Enter your text and click "Detect AI Content" to see analysis here.</p>
                    <p><small>Results will show AI confidence score and writing style analysis.</small></p>
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
                    <h3>🤖 Advanced AI Detection</h3>
                    <p>Our algorithm analyzes vocabulary complexity, sentence structure, and writing patterns to identify AI-generated content.</p>
                </div>
                <div class="feature-item">
                    <h3>📊 Detailed Analysis</h3>
                    <p>Get comprehensive reports with confidence scores, writing style analysis, and specific indicators found.</p>
                </div>
                <div class="feature-item">
                    <h3>⚡ Fast Results</h3>
                    <p>Get your AI detection results in seconds. No waiting, no delays, just accurate analysis.</p>
                </div>
                <div class="feature-item">
                    <h3>📱 Mobile Friendly</h3>
                    <p>Access the AI detector from any device. Works perfectly on desktop, tablet, and mobile.</p>
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
                    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9522930547476630"
     crossorigin="anonymous"></script>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>AI Analysis</h3>
                        <p>Our algorithm analyzes multiple factors including vocabulary complexity, sentence structure, and writing patterns.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Get Results</h3>
                        <p>Receive a detailed report with AI confidence score, writing style analysis, and specific indicators.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detection Indicators -->
        <div class="indicators-section">
            <h2>🔍 What We Analyze</h2>
            <div class="indicators-grid">
                <div class="indicator-item">
                    <h4>📚 Vocabulary Complexity</h4>
                    <p>Analyzes word complexity and unusual vocabulary patterns that may indicate AI generation.</p>
                </div>
                <div class="indicator-item">
                    <h4>🏗️ Sentence Structure</h4>
                    <p>Examines sentence length consistency, complexity patterns, and structural uniformity.</p>
                </div>
                <div class="indicator-item">
                    <h4>🔄 Repetitive Patterns</h4>
                    <p>Detects repetitive phrases, common AI transition words, and overused expressions.</p>
                </div>
                <div class="indicator-item">
                    <h4>🎭 Writing Style</h4>
                    <p>Analyzes formal language usage, passive voice patterns, and overall writing consistency.</p>
                </div>
                <div class="indicator-item">
                    <h4>🧠 Semantic Coherence</h4>
                    <p>Evaluates logical flow and topic consistency across sentences and paragraphs.</p>
                </div>
                <div class="indicator-item">
                    <h4>💬 Common AI Phrases</h4>
                    <p>Identifies frequently used AI-generated content phrases and transition words.</p>
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
                        <li>Use for academic integrity verification</li>
                        <li>Check content before submission</li>
                        <li>Review flagged sections carefully</li>
                        <li>Consider context and citations</li>
                        <li>Use as a guide, not definitive proof</li>
                    </ul>
                </div>
                <div class="guideline-item">
                    <h4>⚠️ Important Notes</h4>
                    <ul>
                        <li>Free tier: 1,500 words per check</li>
                        <li>Daily limits: 10 checks (guests), 30 checks (users)</li>
                        <li>Results are estimates with confidence levels</li>
                        <li>Consider human writing variation</li>
                        <li>False positives/negatives possible</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- History Section -->
        @auth
            <div class="history-section">
                <h2>📚 Recent Detections</h2>
                <p>View your AI detection history and download detailed reports.</p>
                <a href="{{ route('ai-detector.history') }}" class="btn btn-secondary">View Full History</a>
            </div>
        @endauth

        <!-- Footer -->
        <div class="ai-detector-footer">
            <p>&copy; 2025 ReadProjectTopics.com - Academic Resources Platform</p>
            <p>
                <a href="#">Privacy Policy</a> | 
                <a href="#">Terms of Service</a> | 
                <a href="#">Contact Support</a>
            </p>
        </div>
    </div>

    <script src="{{ asset('js/ai-detector/script.js') }}"></script>
    
    <style>
        .features-section,
        .how-it-works,
        .indicators-section,
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
        .indicators-section h2,
        .guidelines h2 {
            color: #374151;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
        }

        .features-grid,
        .indicators-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .feature-item h3,
        .indicator-item h4 {
            color: #23A455;
            margin-bottom: 0.75rem;
            font-size: 1.125rem;
        }

        .feature-item p,
        .indicator-item p {
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

        /* AI-specific styles */
        .ai-score-display {
            text-align: center;
            margin: 2rem 0;
        }

        .ai-score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            font-weight: 700;
            color: white;
            position: relative;
        }

        .ai-score-circle.ai-likely {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }

        .ai-score-circle.ai-uncertain {
            background: linear-gradient(135deg, #F59E0B, #D97706);
        }

        .ai-score-circle.human-likely {
            background: linear-gradient(135deg, #10B981, #059669);
        }

        .ai-score-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .ai-score-badge.ai-likely {
            background: #FEE2E2;
            color: #991B1B;
        }

        .ai-score-badge.ai-uncertain {
            background: #FEF3C7;
            color: #92400E;
        }

        .ai-score-badge.human-likely {
            background: #D1FAE5;
            color: #065F46;
        }

        .confidence-level {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .confidence-level.high {
            background: #FEE2E2;
            color: #991B1B;
        }

        .confidence-level.medium {
            background: #FEF3C7;
            color: #92400E;
        }

        .confidence-level.low {
            background: #D1FAE5;
            color: #065F46;
        }

        .indicators-list {
            margin-top: 1.5rem;
        }

        .indicator-item-analysis {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.75rem;
        }

        .indicator-name {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .indicator-score {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .writing-style-summary {
            background: #F0F9FF;
            border: 1px solid #BAE6FD;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .writing-style-summary h4 {
            color: #0C4A6E;
            margin-bottom: 0.5rem;
        }

        .writing-style-summary p {
            color: #075985;
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .features-grid,
            .indicators-grid,
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