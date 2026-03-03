// Plagiarism Checker JavaScript
class PlagiarismChecker {
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

        this.wordLimit = 1000;
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
        const form = document.getElementById('plagiarismForm');
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
                this.downloadReport(e.target.dataset.checkId);
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
            this.showAlert('Please enter at least 50 words to check for plagiarism.', 'warning');
            return;
        }

        if (this.countWords(text) > this.wordLimit) {
            this.showAlert(`Text exceeds the ${this.wordLimit} word limit for free checks.`, 'warning');
            return;
        }

        this.isChecking = true;
        this.checkBtn.disabled = true;
        this.checkBtn.innerHTML = '<div class="loading"><div class="spinner"></div>Checking...</div>';

        try {
            const formData = new FormData();
            formData.append('text', text);

            const response = await fetch('/plagiarism-checker/check', {
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
                this.showAlert(data.message || 'An error occurred while checking for plagiarism.', 'error');
                if (data.limits) {
                    this.updateUsageLimits(data.limits);
                }
            }
        } catch (error) {
            console.error('Plagiarism check error:', error);
            this.showAlert('Network error. Please check your connection and try again.', 'error');
        } finally {
            this.isChecking = false;
            this.checkBtn.disabled = false;
            this.checkBtn.innerHTML = '<div class="loading"><div class="spinner"></div>Check for Plagiarism</div>';
        }
    }

    displayResults(data) {
        if (!this.resultsSection) return;

        const scoreClass = data.score <= 10 ? 'low' : data.score <= 25 ? 'medium' : 'high';
        const scoreLabel = data.score <= 10 ? 'Low Risk' : data.score <= 25 ? 'Medium Risk' : 'High Risk';
        
        let sourcesHtml = '';
        if (data.sources && data.sources.length > 0) {
            sourcesHtml = `
                <div class="sources-list">
                    <h4>Sources Found:</h4>
                    ${data.sources.map(source => `
                        <div class="source-item">
                            <a href="${source.url}" target="_blank" class="source-url">${source.title}</a>
                            <div class="source-match">${source.match}% match</div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        this.resultsSection.innerHTML = `
            <div class="check-status status-success">
                <h2>Plagiarism Check Complete</h2>
            </div>
            
            <div class="score-display">
                <div class="score-circle ${scoreClass}">${data.score}%</div>
                <div class="score-label">${scoreLabel}</div>
                <div class="score-description">
                    ${data.score <= 10 ? 'Your content appears to be original with minimal similarity to existing sources.' :
                      data.score <= 25 ? 'Some similarity found. Consider reviewing and revising these sections.' :
                      'High similarity detected. Significant revision recommended.'}
                </div>
            </div>

            <div class="check-details">
                <p><strong>Word Count:</strong> ${data.word_count}</p>
                <p><strong>Check Time:</strong> ${data.check_time}s</p>
                <p><strong>Check ID:</strong> #${data.check_id}</p>
            </div>

            ${sourcesHtml}

            <div class="results-actions">
                <button onclick="plagiarismChecker.downloadReport(${data.check_id})" class="btn btn-secondary">
                    Download Report
                </button>
                <button onclick="plagiarismChecker.showHistory()" class="btn btn-primary">
                    View History
                </button>
            </div>
        `;

        this.resultsSection.scrollIntoView({ behavior: 'smooth' });
    }

    updateUsageLimits(usage) {
        if (!this.usageLimits || !usage) return;

        const checksUsed = this.getTotalChecks() - usage.remaining_checks;
        const wordsUsed = this.getTotalWords() - usage.remaining_words;

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

    getTotalChecks() {
        const totalElement = this.usageLimits?.querySelector('.limit-item .limit-value');
        return totalElement ? parseInt(totalElement.textContent) : 20;
    }

    getTotalWords() {
        return 1000; // Fixed limit for free version
    }

    showHistory() {
        window.location.href = '/plagiarism-checker/history';
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

    downloadReport(checkId) {
        const link = document.createElement('a');
        link.href = `/plagiarism-checker/report/${checkId}`;
        link.download = `plagiarism-report-${checkId}.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
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
}

// Initialize the plagiarism checker when the page loads
let plagiarismChecker;

document.addEventListener('DOMContentLoaded', () => {
    plagiarismChecker = new PlagiarismChecker();
});

// Global function for buttons (for backwards compatibility)
window.performPlagiarismCheck = () => {
    if (plagiarismChecker) {
        plagiarismChecker.performCheck();
    }
};

window.downloadReport = (checkId) => {
    if (plagiarismChecker) {
        plagiarismChecker.downloadReport(checkId);
    }
};

window.showHistory = () => {
    if (plagiarismChecker) {
        plagiarismChecker.showHistory();
    }
};
