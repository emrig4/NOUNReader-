/**
 * Popup Notification System
 * Real-time notifications for credit management and wallet activities
 */

class PopupNotificationSystem {
    constructor(options = {}) {
        this.options = {
            apiEndpoint: '/api/popups',
            walletEndpoint: '/api/popups/wallet',
            refreshInterval: options.refreshInterval || 30000, // 30 seconds
            maxNotifications: options.maxNotifications || 5,
            enableAutoRefresh: options.enableAutoRefresh !== false,
            ...options
        };
        
        this.notifications = [];
        this.isRefreshing = false;
        this.refreshTimer = null;
        
        this.init();
    }
    
    init() {
        this.createNotificationContainer();
        this.loadInitialNotifications();
        
        if (this.options.enableAutoRefresh) {
            this.startAutoRefresh();
        }
        
        // Global event listeners for user actions
        this.setupEventListeners();
    }
    
    /**
     * Create notification container if it doesn't exist
     */
    createNotificationContainer() {
        if (document.getElementById('popup-notifications-container')) {
            this.container = document.getElementById('popup-notifications-container');
            return;
        }
        
        this.container = document.createElement('div');
        this.container.id = 'popup-notifications-container';
        this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
        this.container.style.cssText = 'pointer-events: none;';
        document.body.appendChild(this.container);
    }
    
    /**
     * Setup global event listeners
     */
    setupEventListeners() {
        // Listen for credit-related actions
        document.addEventListener('credit-deducted', (event) => {
            this.handleCreditDeducted(event.detail);
        });
        
        document.addEventListener('insufficient-credit', (event) => {
            this.handleInsufficientCredit(event.detail);
        });
        
        document.addEventListener('wallet-updated', (event) => {
            this.handleWalletUpdated(event.detail);
        });
        
        // Also listen for custom events from AJAX responses
        document.addEventListener('ajax:success', (event) => {
            if (event.detail.response?.popup_notification) {
                this.showNotification(event.detail.response.popup_notification);
            }
        });
    }
    
    /**
     * Handle credit deducted events
     */
    handleCreditDeducted(detail) {
        const { amount, operation, resource_title } = detail;
        
        const message = operation === 'read' 
            ? `You have been debited ${amount} credit units for reading: ${resource_title}`
            : `You have been debited ${amount} credit units for downloading: ${resource_title}`;
            
        this.showNotification({
            type: 'success',
            title: 'Credit Deducted',
            message: message,
            position: 'bottom-right'
        });
        
        // Refresh wallet stats after deduction
        this.refreshWalletStats();
    }
    
    /**
     * Handle insufficient credit events
     */
    handleInsufficientCredit(detail) {
        const { operation, required_amount, current_balance, resource_title, is_registered } = detail;
        
        const message = operation === 'read'
            ? `You don't have sufficient credit to read ${resource_title}. Required: ${required_amount} units. Your balance: ${current_balance} units.`
            : `You don't have sufficient credit to download ${resource_title}. Required: ${required_amount} units. Your balance: ${current_balance} units.`;
            
        this.showNotification({
            type: 'error',
            title: 'Insufficient Credit',
            message: message,
            position: 'top-center',
            action_url: is_registered ? '/subscription' : '/register',
            action_text: is_registered ? 'Buy Credit Now!' : 'Register & Buy Credits'
        });
    }
    
    /**
     * Handle wallet updated events
     */
    handleWalletUpdated(detail) {
        const { subscription_balance, credit_balance } = detail;
        
        // Show low balance warning
        if (subscription_balance < 10) {
            this.showNotification({
                type: 'warning',
                title: 'Low Credit Balance',
                message: `Your credit balance is ${subscription_balance}. Top up to continue reading and downloading.`,
                position: 'top-right',
                action_url: '/subscription',
                action_text: 'Buy Credits'
            });
        }
    }
    
    /**
     * Show a notification popup
     */
    showNotification(notification) {
        if (this.notifications.length >= this.options.maxNotifications) {
            this.removeOldestNotification();
        }
        
        const notificationElement = this.createNotificationElement(notification);
        this.notifications.push(notification);
        
        // Add to container
        this.container.appendChild(notificationElement);
        
        // Auto-remove after delay (except persistent ones)
        if (!notification.persistent) {
            setTimeout(() => {
                this.removeNotification(notificationElement);
            }, notification.duration || 8000);
        }
        
        return notificationElement;
    }
    
    /**
     * Create notification element
     */
    createNotificationElement(notification) {
        const element = document.createElement('div');
        element.className = `notification-popup notification-${notification.type} transform transition-all duration-300 max-w-sm`;
        
        // Position classes
        const positionClass = this.getPositionClass(notification.position);
        element.className += ` ${positionClass}`;
        
        // Icon based on type
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        
        const icon = icons[notification.type] || icons.info;
        
        element.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-4 pointer-events-auto">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="notification-icon text-${notification.type}-500 text-lg">
                            ${icon}
                        </div>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            ${notification.title}
                        </p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            ${notification.message}
                        </p>
                        ${notification.action_url ? `
                            <div class="mt-3">
                                <a href="${notification.action_url}" 
                                   class="text-sm font-medium text-${notification.type}-600 hover:text-${notification.type}-500">
                                    ${notification.action_text || 'Learn more'}
                                </a>
                            </div>
                        ` : ''}
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button class="notification-close inline-flex text-gray-400 hover:text-gray-600 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add close functionality
        const closeButton = element.querySelector('.notification-close');
        closeButton.addEventListener('click', () => {
            this.removeNotification(element);
        });
        
        // Add click outside to close
        setTimeout(() => {
            document.addEventListener('click', (e) => {
                if (!element.contains(e.target)) {
                    this.removeNotification(element);
                }
            });
        }, 100);
        
        return element;
    }
    
    /**
     * Get CSS classes for notification position
     */
    getPositionClass(position) {
        const positions = {
            'top-left': 'absolute top-4 left-4',
            'top-right': 'absolute top-4 right-4',
            'top-center': 'absolute top-4 left-1/2 transform -translate-x-1/2',
            'bottom-left': 'absolute bottom-4 left-4',
            'bottom-right': 'absolute bottom-4 right-4',
            'bottom-center': 'absolute bottom-4 left-1/2 transform -translate-x-1/2'
        };
        
        return positions[position] || positions['top-right'];
    }
    
    /**
     * Remove notification element
     */
    removeNotification(element) {
        if (element && element.parentNode) {
            element.style.transform = 'translateX(100%)';
            element.style.opacity = '0';
            
            setTimeout(() => {
                if (element.parentNode) {
                    element.parentNode.removeChild(element);
                }
                
                // Remove from notifications array
                const index = this.notifications.indexOf(element);
                if (index > -1) {
                    this.notifications.splice(index, 1);
                }
            }, 300);
        }
    }
    
    /**
     * Remove oldest notification
     */
    removeOldestNotification() {
        if (this.notifications.length > 0) {
            this.removeNotification(this.notifications[0]);
        }
    }
    
    /**
     * Load initial notifications from server
     */
    async loadInitialNotifications() {
        if (!this.isAuthenticatedUser()) return;
        
        try {
            const response = await fetch(`${this.options.apiEndpoint}/unread`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.data) {
                    data.data.forEach(notification => {
                        this.showNotification(notification);
                    });
                }
            }
        } catch (error) {
            console.error('Failed to load initial notifications:', error);
        }
    }
    
    /**
     * Refresh wallet stats and notifications
     */
    async refreshWalletStats() {
        if (!this.isAuthenticatedUser() || this.isRefreshing) return;
        
        this.isRefreshing = true;
        
        try {
            const response = await fetch(`${this.options.walletEndpoint}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.data) {
                    // Show wallet notifications if any
                    if (data.data.length > 0) {
                        data.data.forEach(notification => {
                            this.showNotification(notification);
                        });
                    }
                    
                    // Dispatch wallet updated event
                    document.dispatchEvent(new CustomEvent('wallet-stats-refreshed', {
                        detail: data.balance_info || data.data
                    }));
                }
            }
        } catch (error) {
            console.error('Failed to refresh wallet stats:', error);
        } finally {
            this.isRefreshing = false;
        }
    }
    
    /**
     * Start auto-refresh timer
     */
    startAutoRefresh() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
        }
        
        this.refreshTimer = setInterval(() => {
            this.refreshWalletStats();
        }, this.options.refreshInterval);
    }
    
    /**
     * Stop auto-refresh timer
     */
    stopAutoRefresh() {
        if (this.refreshTimer) {
            clearInterval(this.refreshTimer);
            this.refreshTimer = null;
        }
    }
    
    /**
     * Check if user is authenticated
     */
    isAuthenticatedUser() {
        return document.querySelector('meta[name="csrf-token"]') !== null;
    }
    
    /**
     * Manually trigger notification
     */
    triggerNotification(type, title, message, options = {}) {
        this.showNotification({
            type,
            title,
            message,
            ...options
        });
    }
    
    /**
     * Clear all notifications
     */
    clearAllNotifications() {
        this.notifications.forEach(notification => {
            this.removeNotification(notification);
        });
    }
    
    /**
     * Destroy notification system
     */
    destroy() {
        this.stopAutoRefresh();
        this.clearAllNotifications();
        
        if (this.container && this.container.parentNode) {
            this.container.parentNode.removeChild(this.container);
        }
    }
}

// Initialize popup notification system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if popup notifications are enabled for this page
    const enableNotifications = document.body.dataset.enablePopupNotifications === 'true';
    
    if (enableNotifications) {
        window.popupNotificationSystem = new PopupNotificationSystem({
            enableAutoRefresh: true,
            refreshInterval: 30000 // 30 seconds
        });
        
        // Make it globally accessible
        window.showPopupNotification = (type, title, message, options) => {
            window.popupNotificationSystem.triggerNotification(type, title, message, options);
        };
        
        // Utility functions for triggering events
        window.triggerCreditDeducted = (amount, operation, resourceTitle) => {
            document.dispatchEvent(new CustomEvent('credit-deducted', {
                detail: { amount, operation, resource_title: resourceTitle }
            }));
        };
        
        window.triggerInsufficientCredit = (operation, requiredAmount, currentBalance, resourceTitle, isRegistered = true) => {
            document.dispatchEvent(new CustomEvent('insufficient-credit', {
                detail: { 
                    operation, 
                    required_amount: requiredAmount, 
                    current_balance: currentBalance, 
                    resource_title: resourceTitle,
                    is_registered: isRegistered
                }
            }));
        };
        
        window.triggerWalletUpdated = (subscriptionBalance, creditBalance) => {
            document.dispatchEvent(new CustomEvent('wallet-updated', {
                detail: { subscription_balance: subscriptionBalance, credit_balance: creditBalance }
            }));
        };
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PopupNotificationSystem;
}