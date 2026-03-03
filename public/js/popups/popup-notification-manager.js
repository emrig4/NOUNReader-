/**
 * Popup Notification Manager
 * Handles popup notifications display, interaction, and API communication
 */

class PopupNotificationManager {
    constructor() {
        this.container = null;
        this.apiUrl = '/popups';
        this.refreshInterval = null;
        this.isInitialized = false;
        this.maxVisiblePopups = 5;
        this.autoDismissDelay = 10000; // 10 seconds
        this.init();
    }

    /**
     * Initialize the popup notification manager
     */
    init() {
        if (this.isInitialized) return;

        this.createContainer();
        this.loadStyles();
        this.loadSweetAlert();
        this.setupEventListeners();
        this.loadUnreadPopups();
        this.startAutoRefresh();

        this.isInitialized = true;
        console.log('Popup Notification Manager initialized');
    }

    /**
     * Create the popup container
     */
    createContainer() {
        this.container = document.querySelector('.popup-container');
        
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'popup-container';
            document.body.appendChild(this.container);
        }
    }

    /**
     * Load popup notification styles
     */
    loadStyles() {
        if (!document.querySelector('link[href*="popup-styles.css"]')) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = '/css/popups/popup-styles.css';
            document.head.appendChild(link);
        }
    }

    /**
     * Load SweetAlert2 library
     */
    loadSweetAlert() {
        if (typeof Swal === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
            script.onload = () => console.log('SweetAlert2 loaded');
            document.head.appendChild(script);

            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/@sweetalert2/theme-default/default.css';
            document.head.appendChild(link);
        }
    }

    /**
     * Setup event listeners
     */
    setupEventListeners() {
        // Container click events
        this.container.addEventListener('click', (e) => {
            const popup = e.target.closest('.popup-notification');
            if (popup) {
                const popupId = popup.dataset.popupId;
                this.markAsRead(popupId);
            }
        });

        // Dismiss button events
        this.container.addEventListener('click', (e) => {
            if (e.target.closest('.popup-close')) {
                e.preventDefault();
                e.stopPropagation();
                const popup = e.target.closest('.popup-notification');
                const popupId = popup.dataset.popupId;
                this.dismissPopup(popupId);
            }
        });

        // Action button events
        this.container.addEventListener('click', (e) => {
            if (e.target.closest('.popup-btn')) {
                e.preventDefault();
                const button = e.target.closest('.popup-btn');
                const popup = e.target.closest('.popup-notification');
                const popupId = popup.dataset.popupId;
                
                if (button.dataset.action === 'dismiss') {
                    this.dismissPopup(popupId);
                } else if (button.dataset.action === 'link' && button.href) {
                    window.open(button.href, button.target || '_self');
                    this.dismissPopup(popupId);
                }
            }
        });

        // Auto dismiss for non-persistent popups
        this.container.addEventListener('mouseenter', (e) => {
            const popup = e.target.closest('.popup-notification');
            if (popup && popup.dataset.persistent === 'false') {
                clearTimeout(popup.autoDismissTimeout);
            }
        });

        this.container.addEventListener('mouseleave', (e) => {
            const popup = e.target.closest('.popup-notification');
            if (popup && popup.dataset.persistent === 'false') {
                this.scheduleAutoDismiss(popup);
            }
        });
    }

    /**
     * Load unread popup notifications
     */
    async loadUnreadPopups() {
        try {
            const response = await fetch(`${this.apiUrl}/unread`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            
            if (data.popups && data.popups.length > 0) {
                this.displayPopups(data.popups);
            }
        } catch (error) {
            console.error('Failed to load unread popups:', error);
        }
    }

    /**
     * Display popup notifications
     */
    displayPopups(popups) {
        const visiblePopups = this.container.querySelectorAll('.popup-notification').length;
        const availableSlots = this.maxVisiblePopups - visiblePopups;

        if (availableSlots <= 0) {
            console.log('Maximum number of popups already visible');
            return;
        }

        popups.slice(0, availableSlots).forEach((popup, index) => {
            setTimeout(() => {
                this.showPopup(popup);
            }, index * 200); // Stagger popup appearance
        });
    }

    /**
     * Show a single popup notification
     */
    showPopup(popup) {
        // Check if popup already exists
        if (this.container.querySelector(`[data-popup-id="${popup.id}"]`)) {
            return;
        }

        const popupHtml = this.createPopupHtml(popup);
        this.container.insertAdjacentHTML('beforeend', popupHtml);

        const popupElement = this.container.lastElementChild;
        
        // Trigger animation
        setTimeout(() => {
            popupElement.classList.add('show');
        }, 50);

        // Auto dismiss for non-persistent popups
        if (!popup.is_persistent) {
            this.scheduleAutoDismiss(popupElement);
        }

        // Add progress bar animation
        if (popup.auto_dismiss_time) {
            const progressBar = popupElement.querySelector('.popup-progress');
            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.style.animationDuration = `${popup.auto_dismiss_time}ms`;
                progressBar.style.animation = 'progress linear';
            }
        }
    }

    /**
     * Create HTML for popup notification
     */
    createPopupHtml(popup) {
        const icon = this.getIcon(popup.type);
        const priorityBadge = this.getPriorityBadge(popup.priority);
        const progressBar = !popup.is_persistent ? '<div class="popup-progress"></div>' : '';

        return `
            <div class="popup-notification ${popup.type}" data-popup-id="${popup.id}" data-persistent="${popup.is_persistent}">
                <div class="popup-header">
                    <div style="display: flex; align-items: center;">
                        ${icon}
                        <h4 class="popup-title">${this.escapeHtml(popup.title)}</h4>
                        ${priorityBadge}
                    </div>
                    <button type="button" class="popup-close">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="popup-body">
                    <p class="popup-message">${this.escapeHtml(popup.message)}</p>
                </div>
                ${popup.action_url || popup.action_text ? this.createActionButtons(popup) : ''}
                ${progressBar}
            </div>
        `;
    }

    /**
     * Create action buttons HTML
     */
    createActionButtons(popup) {
        const buttons = [];

        if (popup.action_url && popup.action_text) {
            buttons.push(`
                <a href="${this.escapeHtml(popup.action_url)}" 
                   class="popup-btn primary" 
                   data-action="link" 
                   target="_blank">
                    ${this.escapeHtml(popup.action_text)}
                </a>
            `);
        }

        buttons.push(`
            <button type="button" 
                    class="popup-btn secondary" 
                    data-action="dismiss">
                Dismiss
            </button>
        `);

        return `
            <div class="popup-footer">
                <div class="popup-actions">
                    ${buttons.join('')}
                </div>
            </div>
        `;
    }

    /**
     * Get icon HTML based on type
     */
    getIcon(type) {
        const icons = {
            info: `<svg class="popup-icon" fill="none" stroke="#3b82f6" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`,
            success: `<svg class="popup-icon" fill="none" stroke="#10b981" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`,
            warning: `<svg class="popup-icon" fill="none" stroke="#f59e0b" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>`,
            error: `<svg class="popup-icon" fill="none" stroke="#ef4444" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`,
            critical: `<svg class="popup-icon" fill="none" stroke="#dc2626" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`
        };

        return icons[type] || icons.info;
    }

    /**
     * Get priority badge HTML
     */
    getPriorityBadge(priority) {
        return `<span class="priority-badge ${priority}">${priority}</span>`;
    }

    /**
     * Schedule auto dismiss for popup
     */
    scheduleAutoDismiss(popupElement) {
        const popupId = popupElement.dataset.popupId;
        popupElement.autoDismissTimeout = setTimeout(() => {
            this.dismissPopup(popupId);
        }, this.autoDismissDelay);
    }

    /**
     * Dismiss a popup notification
     */
    async dismissPopup(popupId) {
        const popupElement = this.container.querySelector(`[data-popup-id="${popupId}"]`);
        if (!popupElement) return;

        try {
            const response = await fetch(`${this.apiUrl}/${popupId}/dismiss`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                credentials: 'same-origin'
            });

            if (response.ok) {
                this.removePopup(popupElement);
                console.log('Popup dismissed successfully');
            } else {
                throw new Error('Failed to dismiss popup');
            }
        } catch (error) {
            console.error('Error dismissing popup:', error);
            // Remove popup locally even if API call fails
            this.removePopup(popupElement);
        }
    }

    /**
     * Mark popup as read
     */
    async markAsRead(popupId) {
        try {
            // Mark as read in the background (don't wait for response)
            fetch(`${this.apiUrl}/${popupId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                credentials: 'same-origin'
            }).catch(error => {
                console.warn('Failed to mark popup as read:', error);
            });
        } catch (error) {
            console.error('Error marking popup as read:', error);
        }
    }

    /**
     * Remove popup from DOM with animation
     */
    removePopup(popupElement) {
        if (!popupElement) return;

        // Clear any auto dismiss timers
        if (popupElement.autoDismissTimeout) {
            clearTimeout(popupElement.autoDismissTimeout);
        }

        // Animate out
        popupElement.style.transform = 'translateX(100%)';
        popupElement.style.opacity = '0';

        setTimeout(() => {
            if (popupElement.parentNode) {
                popupElement.parentNode.removeChild(popupElement);
            }
        }, 300);
    }

    /**
     * Start auto refresh for new notifications
     */
    startAutoRefresh() {
        this.refreshInterval = setInterval(() => {
            this.loadUnreadPopups();
        }, 30000); // Refresh every 30 seconds
    }

    /**
     * Stop auto refresh
     */
    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
    }

    /**
     * Create a new popup notification (for testing)
     */
    async createTestPopup() {
        try {
            const testPopup = {
                user_id: 1, // Replace with actual user ID
                title: 'Test Notification',
                message: 'This is a test popup notification. It should appear in the top-right corner.',
                type: 'info',
                priority: 'medium',
                is_persistent: false
            };

            const response = await fetch(this.apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                credentials: 'same-origin',
                body: JSON.stringify(testPopup)
            });

            if (response.ok) {
                const data = await response.json();
                this.showPopup(data.popup);
                console.log('Test popup created successfully');
            } else {
                throw new Error('Failed to create test popup');
            }
        } catch (error) {
            console.error('Error creating test popup:', error);
        }
    }

    /**
     * Show confirmation dialog
     */
    showConfirmDialog(title, message, callback) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel',
                customClass: {
                    popup: 'popup-notification-swal'
                }
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        } else {
            // Fallback to native confirm
            if (confirm(`${title}\n\n${message}`) && callback) {
                callback();
            }
        }
    }

    /**
     * Show success notification
     */
    showSuccess(title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'success',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        }
    }

    /**
     * Show error notification
     */
    showError(title, message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'error',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK'
            });
        } else {
            alert(`${title}\n\n${message}`);
        }
    }

    /**
     * Escape HTML to prevent XSS
     */
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Destroy the popup manager
     */
    destroy() {
        this.stopAutoRefresh();
        
        // Remove all popup elements
        const popups = this.container.querySelectorAll('.popup-notification');
        popups.forEach(popup => {
            if (popup.autoDismissTimeout) {
                clearTimeout(popup.autoDismissTimeout);
            }
            popup.remove();
        });

        // Remove event listeners
        this.container.removeEventListener('click', this.handleClick);
        
        this.isInitialized = false;
        console.log('Popup Notification Manager destroyed');
    }
}

// CSS for SweetAlert2 custom styles
const customStyles = `
    .popup-notification-swal {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
`;

// Inject custom styles
if (!document.querySelector('#popup-notification-custom-styles')) {
    const styleSheet = document.createElement('style');
    styleSheet.id = 'popup-notification-custom-styles';
    styleSheet.textContent = customStyles;
    document.head.appendChild(styleSheet);
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.popupManager = new PopupNotificationManager();
});

// Export for manual initialization
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PopupNotificationManager;
} else if (typeof window !== 'undefined') {
    window.PopupNotificationManager = PopupNotificationManager;
}
