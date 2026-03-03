{{-- Theme-Optimized Loader for Academic Repository --}}
{{-- Fast, lightweight, and matches your cyan color scheme --}}

<style>
/* Theme-Optimized Loader Styles */
.loader-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(2px);
}

.loader-container {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(45, 172, 227, 0.15);
    border: 1px solid rgba(45, 172, 227, 0.1);
}

/* Theme colors */
.loader-spinner {
    width: 50px;
    height: 50px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #2DACE3;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader-text {
    color: #2DACE3;
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
}

/* Skeleton loading for your two-column layout */
.skeleton-container {
    display: none;
    animation: fadeIn 0.3s ease-in;
}

.skeleton-grid {
    display: grid;
    grid-template-columns: 1fr 2fr;
    gap: 2rem;
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

/* Left column - Document preview */
.skeleton-document {
    aspect-ratio: 210/297;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Right column - Content */
.skeleton-title {
    height: 2rem;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 4px;
    margin-bottom: 1rem;
}

.skeleton-title.short {
    width: 60%;
}

.skeleton-text {
    height: 1rem;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 4px;
    margin-bottom: 0.5rem;
}

.skeleton-text.wide { width: 90%; }
.skeleton-text.medium { width: 70%; }
.skeleton-text.narrow { width: 50%; }

@keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* Network error state */
.error-state {
    display: none;
    text-align: center;
    padding: 2rem;
    color: #dc3545;
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    margin: 2rem auto;
    max-width: 400px;
}

.error-icon {
    color: #dc3545;
    font-size: 3rem;
    margin-bottom: 1rem;
}

.retry-btn {
    background: #2DACE3;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-top: 1rem;
}

.retry-btn:hover {
    background: #1e90cc;
    transform: translateY(-1px);
}

/* Responsive design */
@media (max-width: 768px) {
    .skeleton-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .loader-container {
        margin: 1rem;
        padding: 1.5rem;
    }
}
</style>

{{-- Main Loader Overlay --}}
<div id="loaderOverlay" class="loader-overlay">
    <div class="loader-container">
        <div class="loader-spinner"></div>
        <p class="loader-text">Loading academic resources...</p>
    </div>
</div>

{{-- Skeleton Content (appears after session check) --}}
<div id="skeletonContent" class="skeleton-container">
    <div class="skeleton-grid">
        {{-- Left: Document Preview Skeleton --}}
        <div class="skeleton-document"></div>
        
        {{-- Right: Content Skeleton --}}
        <div>
            <div class="skeleton-title"></div>
            <div class="skeleton-title short"></div>
            <div class="skeleton-text wide"></div>
            <div class="skeleton-text medium"></div>
            <div class="skeleton-text wide"></div>
            <div class="skeleton-text narrow"></div>
            <div class="skeleton-text wide"></div>
        </div>
    </div>
</div>

{{-- Network Error State --}}
<div id="networkError" class="error-state">
    <div class="error-icon">⚠️</div>
    <h3>Connection Issue</h3>
    <p>Unable to load content. Please check your internet connection.</p>
    <button class="retry-btn" onclick="retryLoad()">
        <i class="fas fa-sync-alt"></i> Retry Loading
    </button>
</div>

<script>
// Theme-Optimized Loader JavaScript
class AcademicLoader {
    constructor() {
        this.retryCount = 0;
        this.maxRetries = 3;
        this.init();
    }

    async init() {
        console.log('🎓 Academic Loader initialized');
        
        // Show initial loader
        this.showLoader();
        
        // Check session and load content
        try {
            await this.checkSession();
            await this.loadContent();
        } catch (error) {
            this.handleNetworkError(error);
        }
    }

    async checkSession() {
        try {
            const response = await fetch('/api/session-check', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (!response.ok) {
                throw new Error(`Session check failed: ${response.status}`);
            }

            const data = await response.json();
            return data;
        } catch (error) {
            console.warn('Session check failed:', error);
            return { authenticated: true }; // Continue anyway
        }
    }

    async loadContent() {
        try {
            // Show skeleton content
            this.showSkeleton();
            
            // Load your specific content here
            await this.loadDashboardData();
            await this.loadNavigation();
            await this.loadUserProfile();
            
            // Success - hide all loaders
            this.hideLoader();
            this.hideSkeleton();
            
            console.log('✅ Content loaded successfully');
            
        } catch (error) {
            throw error;
        }
    }

    async loadDashboardData() {
        const endpoints = [
            '/api/dashboard/stats',
            '/api/user/projects',
            '/api/user/downloads'
        ];

        const promises = endpoints.map(async (endpoint) => {
            try {
                const response = await fetch(endpoint);
                if (!response.ok) throw new Error(`${endpoint} failed`);
                return await response.json();
            } catch (error) {
                console.warn(`Failed to load ${endpoint}:`, error);
                return null;
            }
        });

        await Promise.all(promises);
    }

    async loadNavigation() {
        // Simulate navigation loading
        await this.delay(200);
        
        // Update any dynamic navigation elements
        const navItems = document.querySelectorAll('[data-dynamic]');
        navItems.forEach(item => {
            item.style.opacity = '1';
        });
    }

    async loadUserProfile() {
        try {
            const response = await fetch('/api/user/profile');
            if (response.ok) {
                const data = await response.json();
                this.updateUserInterface(data);
            }
        } catch (error) {
            console.warn('Profile loading failed:', error);
        }
    }

    updateUserInterface(userData) {
        // Update user avatar, name, etc.
        const userAvatar = document.querySelector('.user-avatar');
        const userName = document.querySelector('.user-name');
        
        if (userAvatar && userData.avatar) {
            userAvatar.src = userData.avatar;
        }
        
        if (userName && userData.name) {
            userName.textContent = userData.name;
        }
    }

    showLoader() {
        const overlay = document.getElementById('loaderOverlay');
        if (overlay) overlay.style.display = 'flex';
    }

    hideLoader() {
        const overlay = document.getElementById('loaderOverlay');
        if (overlay) overlay.style.display = 'none';
    }

    showSkeleton() {
        const skeleton = document.getElementById('skeletonContent');
        if (skeleton) skeleton.style.display = 'block';
    }

    hideSkeleton() {
        const skeleton = document.getElementById('skeletonContent');
        if (skeleton) skeleton.style.display = 'none';
    }

    handleNetworkError(error) {
        console.error('Network error:', error);
        this.hideLoader();
        this.hideSkeleton();
        
        const errorDiv = document.getElementById('networkError');
        if (errorDiv) errorDiv.style.display = 'block';
    }

    delay(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}

// Global retry function
function retryLoad() {
    const errorDiv = document.getElementById('networkError');
    if (errorDiv) errorDiv.style.display = 'none';
    
    // Reset and reinitialize
    if (window.academicLoader) {
        window.academicLoader = new AcademicLoader();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    window.academicLoader = new AcademicLoader();
});

// Handle page visibility changes (resume loading when tab becomes active)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && window.academicLoader) {
        // Check if content failed to load and retry
        const skeleton = document.getElementById('skeletonContent');
        const overlay = document.getElementById('loaderOverlay');
        
        if (skeleton && skeleton.style.display === 'none' && 
            overlay && overlay.style.display === 'none') {
            // Content loaded, no action needed
            return;
        }
    }
});

// Network status monitoring
window.addEventListener('online', function() {
    console.log('🌐 Connection restored');
    if (window.academicLoader) {
        retryLoad();
    }
});

window.addEventListener('offline', function() {
    console.log('📡 Connection lost');
});
</script>

{{-- Session Management Helper --}}
<script>
// Enhanced session management for your theme
window.SessionManager = {
    checkInterval: null,
    
    startMonitoring() {
        this.checkInterval = setInterval(() => {
            this.validateSession();
        }, 30000); // Check every 30 seconds
    },
    
    stopMonitoring() {
        if (this.checkInterval) {
            clearInterval(this.checkInterval);
        }
    },
    
    async validateSession() {
        try {
            const response = await fetch('/api/validate-session');
            if (response.status === 401) {
                this.handleSessionTimeout();
            }
        } catch (error) {
            console.warn('Session validation failed:', error);
        }
    },
    
    handleSessionTimeout() {
        if (confirm('Your session has expired. Would you like to refresh the page?')) {
            window.location.reload();
        }
    }
};

// Auto-start session monitoring
document.addEventListener('DOMContentLoaded', function() {
    if (window.SessionManager) {
        window.SessionManager.startMonitoring();
    }
});
</script>
