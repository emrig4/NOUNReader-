<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Detection History - ReadProjectTopics.com</title>
    <link rel="stylesheet" href="{{ asset('css/ai-detector/style.css') }}">
</head>
<body>
    <div class="ai-detector-container">
        <!-- Header -->
        <div class="ai-detector-header">
            <h1>📚 AI Detection History</h1>
            <p>View your past AI detection analyses and download detailed reports.</p>
        </div>

        <!-- Back Button -->
        <div class="back-section">
            <a href="{{ route('ai-detector.index') }}" class="btn btn-secondary">
                ← Back to AI Detector
            </a>
        </div>

        @auth
            <!-- User Info -->
            <div class="user-info-section">
                <h3>Welcome, {{ auth()->user()->name }}!</h3>
                <p>You have performed {{ $detections->total() }} AI detection analyses.</p>
            </div>
        @endauth

        <!-- Statistics Summary -->
        <div class="stats-summary">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-value">{{ $detections->total() }}</div>
                    <div class="stat-label">Total Detections</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $detections->where('ai_score', '>=', 70)->count() }}</div>
                    <div class="stat-label">AI Likely</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $detections->whereBetween('ai_score', [40, 69])->count() }}</div>
                    <div class="stat-label">Uncertain</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $detections->where('ai_score', '<', 40)->count() }}</div>
                    <div class="stat-label">Human Likely</div>
                </div>
            </div>
        </div>

        <!-- Detection History -->
        <div class="history-section">
            <h2>Detection History</h2>
            
            @if($detections->count() > 0)
                <div class="history-list">
                    @foreach($detections as $detection)
                        <div class="history-item">
                            <div class="history-header">
                                <div class="history-info">
                                    <span class="history-date">
                                        {{ $detection->created_at->format('M d, Y \a\t g:i A') }}
                                    </span>
                                    <span class="history-word-count">
                                        {{ $detection->word_count }} words
                                    </span>
                                </div>
                                <div class="history-actions">
                                    <span class="ai-score-badge {{ $detection->ai_score >= 70 ? 'ai-likely' : ($detection->ai_score >= 40 ? 'ai-uncertain' : 'human-likely') }}">
                                        {{ $detection->ai_score >= 70 ? 'AI Likely' : ($detection->ai_score >= 40 ? 'Uncertain' : 'Human Likely') }}
                                        ({{ $detection->ai_score }}%)
                                    </span>
                                    <span class="confidence-level {{ $detection->confidence_level }}">
                                        {{ ucfirst($detection->confidence_level) }} Confidence
                                    </span>
                                </div>
                            </div>
                            
                            <div class="history-details">
                                <div class="detection-preview">
                                    <p>{{ Str::limit($detection->original_text, 200) }}</p>
                                </div>
                                
                                <div class="detection-metrics">
                                    <div class="metric">
                                        <span class="metric-label">Detection Time:</span>
                                        <span class="metric-value">{{ $detection->detection_time }}s</span>
                                    </div>
                                    <div class="metric">
                                        <span class="metric-label">Indicators:</span>
                                        <span class="metric-value">{{ $detection->indicators_summary }}</span>
                                    </div>
                                    <div class="metric">
                                        <span class="metric-label">Writing Style:</span>
                                        <span class="metric-value">{{ $detection->style_summary }}</span>
                                    </div>
                                </div>
                                
                                <div class="detection-actions">
                                    <button onclick="aiDetector.showDetectionDetails({{ $detection->id }})" class="btn btn-primary btn-sm">
                                        View Details
                                    </button>
                                    <a href="{{ route('ai-detector.report', $detection->id) }}" class="btn btn-secondary btn-sm">
                                        Download Report
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pagination-section">
                    {{ $detections->links() }}
                </div>
            @else
                <div class="no-history">
                    <div class="empty-state">
                        <div class="empty-icon">🤖</div>
                        <h3>No Detections Yet</h3>
                        <p>You haven't performed any AI detection analyses yet.</p>
                        <a href="{{ route('ai-detector.index') }}" class="btn btn-primary">
                            Start Detecting AI Content
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Detection Detail Modal -->
        <div id="detectionModal" class="modal" style="display: none;">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Detection Details</h3>
                    <button onclick="aiDetector.hideDetectionDetails()" class="modal-close">&times;</button>
                </div>
                <div class="modal-body" id="detectionModalBody">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>

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
        .back-section {
            margin-bottom: 1.5rem;
        }

        .user-info-section {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            border: 1px solid #E5E7EB;
        }

        .user-info-section h3 {
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .stats-summary {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            border: 1px solid #E5E7EB;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: #F9FAFB;
            border-radius: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #23A455;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .history-list {
            margin-top: 1rem;
        }

        .history-item {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
        }

        .history-item:hover {
            border-color: #23A455;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .history-info {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .history-date {
            font-size: 0.875rem;
            color: #6B7280;
        }

        .history-word-count {
            font-size: 0.875rem;
            color: #9CA3AF;
        }

        .history-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .history-details {
            margin-top: 1rem;
        }

        .detection-preview {
            background: #F9FAFB;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .detection-preview p {
            color: #374151;
            margin: 0;
            font-style: italic;
        }

        .detection-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .metric {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .metric-label {
            font-size: 0.75rem;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .metric-value {
            font-size: 0.875rem;
            color: #374151;
            font-weight: 500;
        }

        .detection-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .no-history {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state {
            max-width: 400px;
            margin: 0 auto;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #6B7280;
            margin-bottom: 1.5rem;
        }

        .pagination-section {
            margin-top: 2rem;
            text-align: center;
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 0.75rem;
            max-width: 90%;
            max-height: 90%;
            width: 800px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            border-bottom: 1px solid #E5E7EB;
        }

        .modal-header h3 {
            color: #374151;
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #9CA3AF;
            cursor: pointer;
            padding: 0.25rem;
        }

        .modal-close:hover {
            color: #6B7280;
        }

        .modal-body {
            padding: 1.5rem;
            max-height: 60vh;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .history-header {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .history-info {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .history-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .detection-metrics {
                grid-template-columns: 1fr;
            }
            
            .detection-actions {
                flex-direction: column;
            }
            
            .modal-content {
                margin: 1rem;
                width: calc(100% - 2rem);
            }
        }
    </style>
</body>
</html>