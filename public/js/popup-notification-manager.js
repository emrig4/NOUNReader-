/**
 * Popup Notification Manager
 * A comprehensive popup notification system for Laravel applications
 */

class PopupNotificationManager {
    constructor() {
        this.notifications = [];
        this.config = {
            autoLoad: true,
            displayDuration: 5000,
            maxVisible: 5,
            apiEndpoint: '/api/notifications'
        };
        this.init();
    }

    init() {
        // Load notifications when page is ready
        if (this.config.autoLoad) {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => this.loadNotifications());
            } else {
                this.loadNotifications();
            }
        }

        // Make instance globally available
        window.PopupNotificationManager = this;
    }

    async loadNotifications() {
        try {
            const response = await fetch(`${this.config.apiEndpoint}/unread`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success && data.data) {
                    this.notifications = data.data;
                    this.displayNotifications();
                }
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    displayNotifications() {
        this.notifications.slice(0, this.config.maxVisible).forEach(notification => {
            this.showNotification(notification);
        });
    }

    showNotification(notification) {
        // Remove existing notification with same ID
        const existing = document.getElementById(`popup-${notification.id}`);
        if (existing) {
            existing.remove();
        }

        // Create notification element
        const popup = this.createNotificationElement(notification);
        document.body.appendChild(popup);

        // Animate in
        setTimeout(() => {
            popup.classList.add('show');
        }, 10);

        // Auto remove after duration
        if (notification.auto_hide !== false) {
            setTimeout(() => {
                this.hideNotification(popup);
            }, notification.display_duration || this.config.displayDuration);
        }

        return popup;
    }

    createNotificationElement(notification) {
        const popup = document.createElement('div');
        popup.id = `popup-${notification.id}`;
        popup.className = `popup-notification popup-${notification.type} popup-${notification.position}`;

        // Create content
        const content = `
            <div class="popup-content">
                <div class="popup-header">
                    <span class="popup-title">${this.escapeHtml(notification.title)}</span>
                    <button class="popup-close" onclick="PopupNotificationManager.hideNotification(this.closest('.popup-notification'))">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M12.854 3.146a.5.5 0 0 0-.708 0L8 7.293 3.854 3.146a.5.5 0 1 0-.708.708L7.293 8l-4.147 4.146a.5.5 0 0 0 .708.708L8 8.707l4.146 4.147a.5.5 0 0 0 .708-.708L8.707 8l4.147-4.146a.5.5 0 0 0 0-.708z"/>
                        </svg>
                    </button>
                </div>
                <div class="popup-message">${this.escapeHtml(notification.message)}</div>
                ${this.createActionButton(notification)}
            </div>
        `;

        popup.innerHTML = content;

        // Add click handlers
        popup.addEventListener('click', (e) => {
            if (!e.target.closest('.popup-close') && !e.target.closest('.popup-action')) {
                this.handleNotificationClick(notification);
            }
        });

        return popup;
    }

    createActionButton(notification) {
        if (notification.action_url && notification.action_text) {
            return `
                <div class="popup-action">
                    <a href="${this.escapeHtml(notification.action_url)}" class="popup-action-btn">
                        ${this.escapeHtml(notification.action_text)}
                    </a>
                </div>
            `;
        }
        return '';
    }

    handleNotificationClick(notification) {
        // Mark as read
        this.markAsRead(notification.id);

        // Navigate if action_url exists
        if (notification.action_url) {
            window.location.href = notification.action_url;
        } else {
            // Just hide the notification
            this.hideNotification(document.getElementById(`popup-${notification.id}`));
        }
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`${this.config.apiEndpoint}/${notificationId}/read`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    // Remove from local notifications array
                    this.notifications = this.notifications.filter(n => n.id !== notificationId);
                }
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    hideNotification(popupElement) {
        if (popupElement) {
            popupElement.classList.remove('show');
            setTimeout(() => {
                if (popupElement.parentNode) {
                    popupElement.remove();
                }
            }, 300);
        }
    }

    createTestNotification(type = 'info', customOptions = {}) {
        const testNotification = {
            id: Date.now(),
            type: type,
            title: customOptions.title || `Test ${type.charAt(0).toUpperCase() + type.slice(1)} Notification`,
            message: customOptions.message || `This is a test ${type} notification created at ${new Date().toLocaleTimeString()}.`,
            position: customOptions.position || 'top-right',
            action_url: customOptions.action_url || null,
            action_text: customOptions.action_text || null,
            auto_hide: customOptions.auto_hide !== false,
            display_duration: customOptions.display_duration || 5000
        };

        return this.showNotification(testNotification);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    clearAll() {
        document.querySelectorAll('.popup-notification').forEach(popup => {
            this.hideNotification(popup);
        });
    }

    getVisibleCount() {
        return document.querySelectorAll('.popup-notification.show').length;
    }
}

// Initialize the popup manager
document.addEventListener('DOMContentLoaded', function() {
    window.popupManager = new PopupNotificationManager();
});

// Make it globally available
window.PopupNotificationManager = PopupNotificationManager;