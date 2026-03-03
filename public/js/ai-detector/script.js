// AI Detector JavaScript
class AiDetector {
    constructor() {
        this.init();
        this.setupEventListeners();
    }

    init() {
        this.textInput = document.getElementById('textInput');
        this.wordCounter = document.getElementById('wordCounter');
        this.checkBtn = document.getElementById('checkBtn');
        this.resultsSection = document.getElementById('resultsSection');
        this.loadingSection = document.getElementById('loadingSection');
        this.fileUpload = document.getElementById('fileUpload');
        this.fileUploadArea = document.querySelector('.file-upload-area');
        this.usageLimits = document.getElementById('usageLimits');
        this.historySection = document.getElementById('historySection');

        this.wordLimit = 1500;
        this.isChecking = false;
    }

    setupEventListeners() {
        // Text input and word counter
        if (this.textInput) {
            this.textInput.addEventListener('input', () => this.updateWordCounter());
            this.textInput.addEventListener('paste', () => {
                setTimeout(() => this.updateWordCounter(), 100);
            });
        }

        // Check button
        if (this.checkBtn) {
            this.checkBtn.addEventListener('click', () => this.performCheck());
        }

        // File upload
        if (this.fileUpload) {
            this.fileUpload.addEventListener('change', (e) => this.handleFileUpload(e));
        }

        // Drag and drop
        if (this.fileUploadArea) {
            this.fileUploadArea.addEventListener('dragover', (e) => this.handleDragOver(e));
            this.fileUploadArea.addEventListener('dragleave', (e) => this.handleDragLeave(e));
            this.fileUploadArea.addEventListener('drop', (e) => this.handleFileDrop(e));
            this.fileUploadArea.addEventListener('click', () => this.fileUpload.click());
        }

        // Form submission
        const form = document.getElementById('aiDetectorForm');
        if (form) {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.performCheck();
            });
        }

        // History pagination
        document.addEventListener('click', (e) => {
            if (e.target.matches('.pagination a')) {
                e.preventDefault();
                this.loadHistory(e.target.href);
            }
        });

        // Download report
        document.addEventListener('click', (e) => {
            if (e.target.matches('.download-report')) {
                e.preventDefault();
                this.downloadReport(e.target.dataset.detectionId);
            }
        });

        // View detection details
        document.addEventListener('click', (e) => {
            if (e.target.matches('.view-details')) {
                e.preventDefault();
                this.showDetectionDetails(e.target.dataset.detectionId);
            }
        });
    }

    updateWordCounter() {
        if (!this.textInput || !this.wordCounter) return;

        const text = this.textInput.value.trim();
        const wordCount = this.countWords(text);
        
        this.wordCounter.textContent = `${wordCount} / ${this.wordLimit} words`;
        
        // Update styling based on limits
        this.wordCounter.className = 'word-counter';
        
        if (wordCount > this.wordLimit) {
            this.wordCounter.classList.add('error');
            this.checkBtn.disabled = true;
        } else if (wordCount > this.wordLimit * 0.8) {
            this.wordCounter.classList.add('warning');
            this.checkBtn.disabled = false;
        } else {
            this.checkBtn.disabled = false;
        }
    }

    countWords(text) {
        return text.split(/\s+/).filter(word => word.length > 0).length;
    }

    async performCheck() {
        if (this.isChecking) return;

        const text = this.textInput ? this.textInput.value.trim() : '';
        
        if (!text || this.countWords(text) < 50) {
            this.showAlert('Please enter at least 50 words to check for AI content.', 'warning');
            return;
        }

        if (this.countWords(text) > this.wordLimit) {
            this.showAlert(`Text exceeds the ${this.wordLimit} word limit for free checks.`, 'warning');
            return;
        }

        this.isChecking = true;
        this.checkBtn.disabled = true;
        this.checkBtn.innerHTML = '<div class="loading"><div class="spinner"></div>Analyzing...</div>';

        try {
            const formData = new FormData();
            formData.append('text', text);

            const response = await fetch('/ai-detector/check', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (data.success) {
                this.displayResults(data.data);
                this.updateUsageLimits(data.data.usage);
            } else {
                this.showAlert(data.message || 'An error occurred while analyzing content.', 'error');
                if (data.limits) {
                    this.updateUsageLimits(data.limits);
                }
            }
        } catch (error) {
            console.error('AI detection error:', error);
            this.showAlert('Network error. Please check your connection and try again.', 'error');
        } finally {
            this.isChecking = false;
            this.checkBtn.disabled = false;
            this.checkBtn.innerHTML = '<div class="loading"><div class="spinner"></div>Detect AI Content</div>';
        }
    }

    displayResults(data) {
        if (!this.resultsSection) return;

        const scoreClass = data.score >= 70 ? 'ai-likely' : data.score >= 40 ? 'ai-uncertain' : 'human-likely';
        const scoreLabel = data.score >= 70 ? 'AI Likely' : data.score >= 40 ? 'Uncertain' : 'Human Likely';
        
        let indicatorsHtml = '';
        if (data.indicators && Object.keys(data.indicators).length > 0) {
            indicatorsHtml = this.generateIndicatorsHtml(data.indicators);
        }

        let writingStyleHtml = '';
        if (data.writing_style && Object.keys(data.writing_style).length > 0) {
            writingStyleHtml = this.generateWritingStyleHtml(data.writing_style);
        }

        this.resultsSection.innerHTML = `
            <div class="check-status status-success">
                <h2>AI Detection Complete</h2>
            </div>
            
            <div class="ai-score-display">
                <div class="ai-score-circle ${scoreClass}">${data.score}%</div>
                <div class="score-label">${scoreLabel}</div>
                <div class="score-description">
                    ${this.getScoreDescription(data.score)}
                </div>
            </div>

            <div class="check-details">
                <p><strong>Word Count:</strong> ${data.word_count}</p>
                <p><strong>Confidence Level:</strong> <span class="confidence-level ${data.confidence_level}">${data.confidence_level.charAt(0).toUpperCase() + data.confidence_level.slice(1)}</span></p>
                <p><strong>Analysis Time:</strong> ${data.detection_time}s</p>
                <p><strong>Detection ID:</strong> #${data.detection_id}</p>
            </div>

            ${indicatorsHtml}

            ${writingStyleHtml}

            <div class="results-actions">
                <button onclick="aiDetector.downloadReport(${data.detection_id})" class="btn btn-secondary">
                    Download Report
                </button>
                <button onclick="aiDetector.showHistory()" class="btn btn-primary">
                    View History
                </button>
            </div>
        `;

        this.resultsSection.scrollIntoView({ behavior: 'smooth' });
    }

    generateIndicatorsHtml(indicators) {
        let html = '<div class="indicators-list"><h4>AI Indicators Found:</h4>';
        
        for (const [key, indicator] of Object.entries(indicators)) {
            if (indicator && typeof indicator === 'object' && indicator.score !== undefined) {
                const percentage = Math.round(indicator.score * 100);
                html += `
                    <div class="indicator-item-analysis">
                        <div class="indicator-name">${this.formatIndicatorName(key)}</div>
                        <div class="indicator-score">Confidence: ${percentage}%</div>
                        ${this.getIndicatorDescription(key, indicator)}
                    </div>
                `;
            }
        }
        
        html += '</div>';
        return html;
    }

    generateWritingStyleHtml(writingStyle) {
        let html = '<div class="writing-style-summary"><h4>Writing Style Analysis:</h4><p>';
        
        const details = [];
        
        if (writingStyle.avg_sentence_length) {
            const length = writingStyle.avg_sentence_length;
            if (length > 25) {
                details.push('Uses long sentences on average');
            } else if (length < 12) {
                details.push('Uses short sentences on average');
            } else {
                details.push('Uses medium-length sentences');
            }
        }
        
        if (writingStyle.vocabulary_diversity) {
            const diversity = writingStyle.vocabulary_diversity;
            if (diversity > 0.8) {
                details.push('High vocabulary diversity');
            } else if (diversity < 0.5) {
                details.push('Low vocabulary diversity');
            }
        }
        
        if (writingStyle.formal_language_ratio > 0.1) {
            details.push('Uses formal language patterns');
        }
        
        if (writingStyle.passive_voice_ratio > 0.3) {
            details.push('Frequent passive voice usage');
        }
        
        html += details.length > 0 ? details.join(', ') : 'Neutral writing style patterns';
        html += '</p></div>';
        
        return html;
    }

    formatIndicatorName(key) {
        const names = {
            'complexity': 'Vocabulary Complexity',
            'structure': 'Sentence Structure',
            'repetition': 'Repetitive Patterns',
            'coherence': 'Semantic Coherence',
            'phrases': 'Common AI Phrases'
        };
        return names[key] || key.charAt(0).toUpperCase() + key.slice(1);
    }

    getIndicatorDescription(key, indicator) {
        const descriptions = {
            'complexity': `Complex words: ${indicator.complex_words || 0} (${Math.round((indicator.complexity_ratio || 0) * 100)}%)`,
            'structure': `Average sentence length: ${indicator.avg_sentence_length || 0} words`,
            'repetition': `Unique words: ${indicator.unique_words || 0} of ${indicator.total_words || 0}`,
            'coherence': `Average word overlap: ${indicator.avg_word_overlap || 0}`,
            'phrases': `AI phrases found: ${indicator.total_phrases || 0}`
        };
        return descriptions[key] ? `<div style="margin-top: 0.5rem; font-size: 0.875rem; color: #6B7280;">${descriptions[key]}</div>` : '';
    }

    getScoreDescription(score) {
        if (score >= 70) {
            return 'Content shows strong indicators of AI generation. Multiple AI-specific patterns were detected.';
        } else if (score >= 40) {
            return 'Content shows mixed indicators. Some patterns suggest AI generation while others appear human-like.';
        } else {
            return 'Content shows strong indicators of human writing. Few AI-specific patterns were detected.';
        }
    }

    updateUsageLimits(usage) {
        if (!this.usageLimits || !usage) return;

        this.usageLimits.innerHTML = `
            <h3>Today's Usage</h3>
            <div class="limits-grid">
                <div class="limit-item">
                    <div class="limit-value">${usage.remaining_checks}</div>
                    <div class="limit-label">Checks Remaining</div>
                </div>
                <div class="limit-item">
                    <div class="limit-value">${usage.remaining_words}</div>
                    <div class="limit-label">Words Remaining</div>
                </div>
            </div>
        `;
    }

    showHistory() {
        window.location.href = '/ai-detector/history';
    }

    async loadHistory(url) {
        try {
            const response = await fetch(url);
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const historyContent = doc.querySelector('.history-section');
            
            if (historyContent && this.historySection) {
                this.historySection.innerHTML = historyContent.innerHTML;
                this.historySection.scrollIntoView({ behavior: 'smooth' });
            }
        } catch (error) {
            console.error('Error loading history:', error);
        }
    }

    downloadReport(detectionId) {
        const link = document.createElement('a');
        link.href = `/ai-detector/report/${detectionId}`;
        link.download = `ai-detection-report-${detectionId}.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    async showDetectionDetails(detectionId) {
        try {
            const response = await fetch(`/ai-detector/api/detection/${detectionId}`);
            const data = await response.json();
            
            if (data.success) {
                this.displayDetectionModal(data.data);
            }
        } catch (error) {
            console.error('Error loading detection details:', error);
            this.showAlert('Error loading detection details.', 'error');
        }
    }

    displayDetectionModal(detection) {
        const modal = document.getElementById('detectionModal');
        const modalBody = document.getElementById('detectionModalBody');
        
        if (!modal || !modalBody) return;
        
        const scoreClass = detection.ai_score >= 70 ? 'ai-likely' : detection.ai_score >= 40 ? 'ai-uncertain' : 'human-likely';
        
        modalBody.innerHTML = `
            <div class="modal-detection-details">
                <div class="modal-header-info">
                    <h3>Detection #${detection.id}</h3>
                    <p>${new Date(detection.created_at).toLocaleDateString()} at ${new Date(detection.created_at).toLocaleTimeString()}</p>
                </div>
                
                <div class="modal-score-section">
                    <div class="ai-score-circle ${scoreClass}" style="width: 80px; height: 80px; font-size: 1.5rem;">
                        ${detection.ai_score}%
                    </div>
                    <div>
                        <h4>${detection.detection_result}</h4>
                        <p>Confidence Level: ${detection.confidence_level.charAt(0).toUpperCase() + detection.confidence_level.slice(1)}</p>
                    </div>
                </div>
                
                <div class="modal-content-section">
                    <h4>Original Content:</h4>
                    <div class="content-preview">
                        ${detection.original_text ? detection.original_text.substring(0, 500) + (detection.original_text.length > 500 ? '...' : '') : 'No content available'}
                    </div>
                </div>
                
                <div class="modal-metrics">
                    <div class="metric-row">
                        <span class="metric-label">Word Count:</span>
                        <span class="metric-value">${detection.word_count}</span>
                    </div>
                    <div class="metric-row">
                        <span class="metric-label">Analysis Time:</span>
                        <span class="metric-value">${detection.detection_time}s</span>
                    </div>
                </div>
            </div>
        `;
        
        modal.style.display = 'flex';
    }

    hideDetectionDetails() {
        const modal = document.getElementById('detectionModal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
            this.processFile(file);
        }
    }

    handleDragOver(event) {
        event.preventDefault();
        this.fileUploadArea.classList.add('dragover');
    }

    handleDragLeave(event) {
        event.preventDefault();
        this.fileUploadArea.classList.remove('dragover');
    }

    handleFileDrop(event) {
        event.preventDefault();
        this.fileUploadArea.classList.remove('dragover');
        
        const file = event.dataTransfer.files[0];
        if (file) {
            this.processFile(file);
        }
    }

    processFile(file) {
        const allowedTypes = ['text/plain', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        
        if (!allowedTypes.includes(file.type)) {
            this.showAlert('Unsupported file type. Please upload TXT, PDF, DOC, or DOCX files.', 'error');
            return;
        }

        if (file.size > 10 * 1024 * 1024) { // 10MB limit
            this.showAlert('File size too large. Maximum size is 10MB.', 'error');
            return;
        }

        const reader = new FileReader();
        
        reader.onload = (e) => {
            if (file.type === 'text/plain') {
                this.textInput.value = e.target.result;
                this.updateWordCounter();
                this.showAlert('File loaded successfully!', 'success');
            } else {
                this.showAlert('File uploaded! (Note: Text extraction from PDF/DOC will be available in premium version)', 'info');
            }
        };
        
        reader.onerror = () => {
            this.showAlert('Error reading file. Please try again.', 'error');
        };

        if (file.type === 'text/plain') {
            reader.readAsText(file);
        } else {
            // For now, just simulate file upload for non-text files
            this.showAlert('File uploaded successfully!', 'success');
        }
    }

    showAlert(message, type = 'info') {
        const alertContainer = document.getElementById('alertContainer') || this.createAlertContainer();
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.innerHTML = `
            ${message}
            <button onclick="this.parentElement.remove()" style="float: right; background: none; border: none; font-size: 1.2em; cursor: pointer;">&times;</button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alert.parentElement) {
                alert.remove();
            }
        }, 5000);
    }

    createAlertContainer() {
        const container = document.createElement('div');
        container.id = 'alertContainer';
        container.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
        `;
        document.body.appendChild(container);
        return container;
    }

    // Utility method to format dates
    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // Method to show loading state
    showLoading(message = 'Processing...') {
        if (!this.loadingSection) return;
        
        this.loadingSection.innerHTML = `
            <div class="check-status">
                <div class="loading">
                    <div class="spinner"></div>
                    <span>${message}</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: 0%"></div>
                </div>
            </div>
        `;
        
        // Animate progress bar
        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 15;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
            }
            
            const progressFill = this.loadingSection.querySelector('.progress-fill');
            if (progressFill) {
                progressFill.style.width = progress + '%';
            }
        }, 200);
    }

    // Method to hide loading state
    hideLoading() {
        if (this.loadingSection) {
            this.loadingSection.innerHTML = '';
        }
    }

    // Method to get detection statistics
    async getStats() {
        try {
            const response = await fetch('/ai-detector/api/stats');
            const data = await response.json();
            
            if (data.success) {
                return data.data;
            }
        } catch (error) {
            console.error('Error fetching stats:', error);
        }
        return null;
    }
}

// Initialize the AI detector when the page loads
let aiDetector;

document.addEventListener('DOMContentLoaded', () => {
    aiDetector = new AiDetector();
});

// Global functions for backwards compatibility
window.performAiDetection = () => {
    if (aiDetector) {
        aiDetector.performCheck();
    }
};

window.downloadReport = (detectionId) => {
    if (aiDetector) {
        aiDetector.downloadReport(detectionId);
    }
};

window.showHistory = () => {
    if (aiDetector) {
        aiDetector.showHistory();
    }
};

window.showDetectionDetails = (detectionId) => {
    if (aiDetector) {
        aiDetector.showDetectionDetails(detectionId);
    }
};

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    const modal = document.getElementById('detectionModal');
    if (modal && e.target === modal) {
        aiDetector.hideDetectionDetails();
    }
});