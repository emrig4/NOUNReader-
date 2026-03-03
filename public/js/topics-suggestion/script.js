/**
 * Research Topics Suggestion Tool - JavaScript
 * Handles form interactions, API calls, and UI updates
 */

document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const topicsForm = document.getElementById('topicsForm');
    const departmentSelect = document.getElementById('department');
    const typeSelect = document.getElementById('type');
    const loadingSection = document.getElementById('loadingSection');
    const resultsSection = document.getElementById('resultsSection');
    const topicsList = document.getElementById('topicsList');
    const noResults = document.getElementById('noResults');
    const searchCriteria = document.getElementById('searchCriteria');
    const searchInfo = document.getElementById('searchInfo');
    const exportBtn = document.getElementById('exportBtn');
    const newSearchBtn = document.getElementById('newSearchBtn');
    const resultsTitle = document.getElementById('resultsTitle');

    // Popular field links
    const popularFieldLinks = document.querySelectorAll('.popular-field-link');

    // Form submission handler
    topicsForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const department = departmentSelect.value;
        const type = typeSelect.value;

        if (!department || !type) {
            showError('Please select both department and type of work.');
            return;
        }

        getTopicSuggestions(department, type);
    });

    // Popular field click handlers
    popularFieldLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const department = this.getAttribute('data-department');
            if (department) {
                departmentSelect.value = department;
                typeSelect.value = 'project'; // Default to project
                getTopicSuggestions(department, 'project');
            }
        });
    });

    // New search button handler
    newSearchBtn.addEventListener('click', function() {
        hideResults();
        topicsForm.reset();
    });

    // Export button handler
    exportBtn.addEventListener('click', function() {
        exportCurrentResults();
    });

    /**
     * Get topic suggestions from API
     */
    function getTopicSuggestions(department, type) {
        showLoading();

        const requestData = {
            department: department,
            type: type
        };

        fetch('/api/topics-suggestion/suggestions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                displaySuggestions(data.data.suggestions, department, type, data.data.total_found);
            } else {
                showError('Failed to get topic suggestions. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
            showError('An error occurred while fetching suggestions. Please try again.');
        });
    }

    /**
     * Display topic suggestions
     */
    function displaySuggestions(suggestions, department, type, totalFound) {
        if (suggestions.length === 0) {
            showNoResults();
            return;
        }

        // Update search criteria
        searchInfo.innerHTML = `
            <strong>Search Results:</strong> ${totalFound} topics found for 
            <strong>${department}</strong> - ${type.charAt(0).toUpperCase() + type.slice(1)}
        `;

        // Clear previous results
        topicsList.innerHTML = '';

        // Create topic items
        suggestions.forEach((topic, index) => {
            const topicItem = createTopicItem(topic, index);
            topicsList.appendChild(topicItem);
        });

        showResults();
    }

    /**
     * Create a topic item element
     */
    function createTopicItem(topic, index) {
        const item = document.createElement('div');
        item.className = 'topic-item';
        
        const sourceClass = topic.source === 'database' ? 'database' : 'generated';
        const sourceText = topic.source === 'database' ? 'From Database' : 'AI Generated';
        
        item.innerHTML = `
            <div class="topic-header">
                <h3 class="topic-title">${escapeHtml(topic.title)}</h3>
                <div class="topic-meta">
                    <span class="topic-type">${topic.type.charAt(0).toUpperCase() + topic.type.slice(1)}</span>
                    <span class="topic-source">${sourceText}</span>
                </div>
            </div>
            <p class="topic-description">${escapeHtml(topic.description)}</p>
            <div class="topic-actions">
                <button class="save-btn" onclick="saveTopic('${escapeHtml(topic.title)}', '${escapeHtml(topic.department)}', '${topic.type}')">
                    <i class="far fa-heart"></i>
                    Save
                </button>
                <button class="copy-btn" onclick="copyTopic('${escapeHtml(topic.title)}')">
                    <i class="far fa-copy"></i>
                    Copy
                </button>
            </div>
        `;

        // Add animation
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'all 0.3s ease';
            
            requestAnimationFrame(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            });
        }, index * 100);

        return item;
    }

    /**
     * Save a topic to favorites
     */
    function saveTopic(title, department, type) {
        const requestData = {
            title: title,
            department: department,
            type: type
        };

        fetch('/api/topics-suggestion/save-favorite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Topic saved to favorites!', 'success');
                // Update button state
                event.target.closest('.save-btn').classList.add('saved');
                event.target.closest('.save-btn').innerHTML = '<i class="fas fa-heart"></i> Saved';
            }
        })
        .catch(error => {
            console.error('Error saving topic:', error);
            showNotification('Failed to save topic. Please try again.', 'error');
        });
    }

    /**
     * Copy topic to clipboard
     */
    function copyTopic(title) {
        navigator.clipboard.writeText(title).then(() => {
            showNotification('Topic copied to clipboard!', 'success');
        }).catch(() => {
            showNotification('Failed to copy topic.', 'error');
        });
    }

    /**
     * Export current results
     */
    function exportCurrentResults() {
        const topics = document.querySelectorAll('.topic-item');
        const csvContent = "data:text/csv;charset=utf-8,";
        
        // Add header
        csvContent += "Title,Description,Department,Type,Source\n";
        
        // Add topics
        topics.forEach(topic => {
            const title = topic.querySelector('.topic-title').textContent;
            const description = topic.querySelector('.topic-description').textContent;
            const type = topic.querySelector('.topic-type').textContent;
            const source = topic.querySelector('.topic-source').textContent;
            const department = searchInfo.textContent.split('for')[1]?.split('-')[0]?.trim() || '';
            
            csvContent += `"${title}","${description}","${department}","${type}","${source}"\n`;
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "research_topics.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    /**
     * Show loading state
     */
    function showLoading() {
        loadingSection.style.display = 'block';
        resultsSection.style.display = 'none';
    }

    /**
     * Hide loading state
     */
    function hideLoading() {
        loadingSection.style.display = 'none';
    }

    /**
     * Show results
     */
    function showResults() {
        resultsSection.style.display = 'block';
        noResults.style.display = 'none';
        exportBtn.style.display = 'flex';
    }

    /**
     * Show no results
     */
    function showNoResults() {
        resultsSection.style.display = 'block';
        topicsList.innerHTML = '';
        noResults.style.display = 'block';
        exportBtn.style.display = 'none';
    }

    /**
     * Hide results
     */
    function hideResults() {
        resultsSection.style.display = 'none';
        noResults.style.display = 'none';
    }

    /**
     * Show error message
     */
    function showError(message) {
        showNotification(message, 'error');
    }

    /**
     * Show notification
     */
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;

        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1'};
            color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460'};
            border: 1px solid ${type === 'success' ? '#c3e6cb' : type === 'error' ? '#f5c6cb' : '#bee5eb'};
            border-radius: 4px;
            padding: 12px 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 8px;
            max-width: 300px;
            animation: slideIn 0.3s ease;
        `;

        // Add animation keyframes
        if (!document.querySelector('#notification-styles')) {
            const styles = document.createElement('style');
            styles.id = 'notification-styles';
            styles.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(styles);
        }

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Global functions for onclick handlers
    window.saveTopic = saveTopic;
    window.copyTopic = copyTopic;
});