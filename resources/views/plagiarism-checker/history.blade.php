<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plagiarism Check History - ReadProjectTopics.com</title>
    <link rel="stylesheet" href="{{ asset('css/plagiarism-checker/style.css') }}">
</head>
<body>
    <div class="plagiarism-container">
        <!-- Header -->
        <div class="plagiarism-header">
            <h1>📚 Check History</h1>
            <p>View your past plagiarism checks and download detailed reports.</p>
        </div>

        <!-- Navigation -->
        <div class="navigation">
            <a href="{{ route('plagiarism-checker.index') }}" class="btn btn-secondary">← Back to Checker</a>
            @auth
                <div class="user-info">
                    <span>Welcome, {{ auth()->user()->name }}</span>
                </div>
            @endauth
        </div>

        <!-- History Section -->
        <div class="history-section">
            <h2>Your Plagiarism Checks</h2>
            
            @if($checks->count() > 0)
                <div class="history-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $checks->total() }}</div>
                        <div class="stat-label">Total Checks</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $checks->sum('word_count') }}</div>
                        <div class="stat-label">Total Words Checked</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ round($checks->avg('plagiarism_score'), 1) }}%</div>
                        <div class="stat-label">Average Similarity</div>
                    </div>
                </div>

                <div class="history-list">
                    @foreach($checks as $check)
                        <div class="history-item">
                            <div class="history-header">
                                <div class="history-date">
                                    {{ $check->created_at->format('M d, Y \a\t H:i') }}
                                </div>
                                <div class="history-score {{ $check->plagiarism_score <= 10 ? 'low' : ($check->plagiarism_score <= 25 ? 'medium' : 'high') }}">
                                    {{ $check->plagiarism_score }}% Similarity
                                </div>
                            </div>
                            
                            <div class="history-details">
                                <p><strong>Words:</strong> {{ $check->word_count }}</p>
                                <p><strong>Check Time:</strong> {{ $check->check_time }}s</p>
                                <p><strong>Status:</strong> 
                                    <span class="status-badge status-{{ $check->status }}">
                                        {{ ucfirst($check->status) }}
                                    </span>
                                </p>
                            </div>

                            @if($check->sources && count($check->sources) > 0)
                                <div class="sources-summary">
                                    <strong>Sources Found:</strong> {{ count($check->sources) }}
                                    <ul class="sources-list-compact">
                                        @foreach(array_slice($check->sources, 0, 3) as $source)
                                            <li>
                                                <a href="{{ $source['url'] ?? '#' }}" target="_blank" class="source-link">
                                                    {{ $source['title'] ?? 'Unknown Source' }}
                                                </a>
                                                <span class="match-percentage">({{ $source['match'] ?? 0 }}% match)</span>
                                            </li>
                                        @endforeach
                                        @if(count($check->sources) > 3)
                                            <li class="more-sources">
                                                +{{ count($check->sources) - 3 }} more sources
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            @endif

                            <div class="history-actions">
                                <button onclick="downloadReport({{ $check->id }})" class="btn btn-sm btn-secondary">
                                    📄 Download Report
                                </button>
                                <button onclick="viewDetails({{ $check->id }})" class="btn btn-sm btn-primary">
                                    👁️ View Details
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($checks->hasPages())
                    <div class="pagination">
                        @if($checks->previousPageUrl())
                            <a href="{{ $checks->previousPageUrl() }}">&laquo; Previous</a>
                        @endif
                        
                        <span class="current">
                            Page {{ $checks->currentPage() }} of {{ $checks->lastPage() }}
                        </span>
                        
                        @if($checks->nextPageUrl())
                            <a href="{{ $checks->nextPageUrl() }}">Next &raquo;</a>
                        @endif
                    </div>
                @endif

            @else
                <div class="empty-state">
                    <div class="empty-icon">📝</div>
                    <h3>No Checks Yet</h3>
                    <p>You haven't performed any plagiarism checks yet. Start by checking your first piece of content!</p>
                    <a href="{{ route('plagiarism-checker.index') }}" class="btn btn-primary">
                        Start Checking
                    </a>
                </div>
            @endif
        </div>

        <!-- Summary Section -->
        @if($checks->count() > 0)
            <div class="summary-section">
                <h2>📊 Summary</h2>
                <div class="summary-grid">
                    <div class="summary-item">
                        <h4>Risk Assessment</h4>
                        @php
                            $lowRisk = $checks->where('plagiarism_score', '<=', 10)->count();
                            $mediumRisk = $checks->whereBetween('plagiarism_score', [11, 25])->count();
                            $highRisk = $checks->where('plagiarism_score', '>', 25)->count();
                        @endphp
                        <div class="risk-breakdown">
                            <div class="risk-item low">
                                <span class="risk-label">Low Risk</span>
                                <span class="risk-count">{{ $lowRisk }}</span>
                            </div>
                            <div class="risk-item medium">
                                <span class="risk-label">Medium Risk</span>
                                <span class="risk-count">{{ $mediumRisk }}</span>
                            </div>
                            <div class="risk-item high">
                                <span class="risk-label">High Risk</span>
                                <span class="risk-count">{{ $highRisk }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="summary-item">
                        <h4>Usage This Month</h4>
                        <div class="usage-stats">
                            <div class="usage-stat">
                                <span class="usage-label">Checks Used</span>
                                <span class="usage-value">{{ $checks->count() }}</span>
                            </div>
                            <div class="usage-stat">
                                <span class="usage-label">Words Checked</span>
                                <span class="usage-value">{{ number_format($checks->sum('word_count')) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="plagiarism-footer">
            <p>&copy; 2025 ReadProjectTopics.com - Academic Resources Platform</p>
        </div>
    </div>

    <script>
        function downloadReport(checkId) {
            const link = document.createElement('a');
            link.href = `/plagiarism-checker/report/${checkId}`;
            link.download = `plagiarism-report-${checkId}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function viewDetails(checkId) {
            // For now, just download the report
            // In a future version, this could show a modal with detailed results
            downloadReport(checkId);
        }

        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers for history items
            const historyItems = document.querySelectorAll('.history-item');
            historyItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (!e.target.closest('.history-actions')) {
                        const checkId = this.querySelector('button').dataset.checkId || 
                                      Array.from(this.querySelectorAll('button')).find(b => b.onclick)?.dataset.checkId;
                        if (checkId) {
                            viewDetails(checkId);
                        }
                    }
                });
            });
        });
    </script>

    <style>
        .navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding: 1rem;
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }

        .user-info {
            color: #23A455;
            font-weight: 500;
        }

        .history-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: #E9F6ED;
            border-radius: 0.5rem;
            border: 1px solid #A3D9B8;
        }

        .stat-item {
            text-align: center;
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
            margin-top: 2rem;
        }

        .history-item {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
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
        }

        .history-date {
            color: #6B7280;
            font-size: 0.875rem;
        }

        .history-score {
            padding: 0.25rem 0.75rem;
            border-radius: 0.25rem;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .history-score.low {
            background: #F0FDF4;
            color: #166534;
        }

        .history-score.medium {
            background: #FFFBEB;
            color: #92400E;
        }

        .history-score.high {
            background: #FEF2F2;
            color: #991B1B;
        }

        .history-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            color: #6B7280;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-completed {
            background: #F0FDF4;
            color: #166534;
        }

        .status-pending {
            background: #FFFBEB;
            color: #92400E;
        }

        .status-failed {
            background: #FEF2F2;
            color: #991B1B;
        }

        .sources-summary {
            margin: 1rem 0;
            padding: 1rem;
            background: #F9FAFB;
            border-radius: 0.25rem;
            border: 1px solid #E5E7EB;
        }

        .sources-summary strong {
            color: #374151;
        }

        .sources-list-compact {
            list-style: none;
            margin: 0.5rem 0 0 0;
            padding: 0;
        }

        .sources-list-compact li {
            padding: 0.25rem 0;
            font-size: 0.875rem;
        }

        .source-link {
            color: #23A455;
            text-decoration: none;
        }

        .source-link:hover {
            text-decoration: underline;
        }

        .match-percentage {
            color: #6B7280;
            font-size: 0.75rem;
        }

        .more-sources {
            color: #9CA3AF;
            font-style: italic;
        }

        .history-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6B7280;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            color: #374151;
            margin-bottom: 1rem;
        }

        .summary-section {
            background: white;
            border-radius: 0.75rem;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            border: 1px solid #E5E7EB;
        }

        .summary-section h2 {
            color: #374151;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .summary-item h4 {
            color: #374151;
            margin-bottom: 1rem;
            font-size: 1.125rem;
        }

        .risk-breakdown {
            display: flex;
            gap: 1rem;
        }

        .risk-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            border-radius: 0.5rem;
            flex: 1;
        }

        .risk-item.low {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
        }

        .risk-item.medium {
            background: #FFFBEB;
            border: 1px solid #FED7AA;
        }

        .risk-item.high {
            background: #FEF2F2;
            border: 1px solid #FECACA;
        }

        .risk-label {
            font-size: 0.875rem;
            color: #6B7280;
            margin-bottom: 0.5rem;
        }

        .risk-count {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .usage-stats {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .usage-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            background: #F9FAFB;
            border-radius: 0.25rem;
        }

        .usage-label {
            color: #6B7280;
        }

        .usage-value {
            font-weight: 600;
            color: #23A455;
        }

        @media (max-width: 768px) {
            .navigation {
                flex-direction: column;
                gap: 1rem;
            }

            .history-stats {
                grid-template-columns: 1fr;
            }

            .history-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .history-details {
                grid-template-columns: 1fr;
            }

            .risk-breakdown {
                flex-direction: column;
            }

            .history-actions {
                flex-direction: column;
            }
        }
    </style>
</body>
</html>
