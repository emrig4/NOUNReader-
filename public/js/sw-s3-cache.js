/**
 * S3 Speed Cache Service Worker
 * Provides offline caching and background synchronization for S3 files
 */

const CACHE_NAME = 's3-speed-cache-v1';
const OFFLINE_CACHE_NAME = 's3-offline-cache-v1';

// Cache strategies
const CACHE_STRATEGIES = {
    CACHE_FIRST: 'cache-first',
    NETWORK_FIRST: 'network-first',
    STALE_WHILE_REVALIDATE: 'stale-while-revalidate',
    NETWORK_ONLY: 'network-only',
    CACHE_ONLY: 'cache-only'
};

// File type configurations
const FILE_CONFIGS = {
    'application/pdf': {
        strategy: CACHE_STRATEGIES.STALE_WHILE_REVALIDATE,
        cacheDuration: 7 * 24 * 60 * 60 * 1000, // 7 days
        maxSize: 50 * 1024 * 1024, // 50MB
        compression: true
    },
    'image/jpeg': {
        strategy: CACHE_STRATEGIES.CACHE_FIRST,
        cacheDuration: 30 * 24 * 60 * 60 * 1000, // 30 days
        maxSize: 10 * 1024 * 1024, // 10MB
        compression: true
    },
    'image/png': {
        strategy: CACHE_STRATEGIES.CACHE_FIRST,
        cacheDuration: 30 * 24 * 60 * 60 * 1000, // 30 days
        maxSize: 10 * 1024 * 1024, // 10MB
        compression: true
    },
    'text/html': {
        strategy: CACHE_STRATEGIES.NETWORK_FIRST,
        cacheDuration: 5 * 60 * 1000, // 5 minutes
        maxSize: 1024 * 1024, // 1MB
        compression: true
    },
    'application/javascript': {
        strategy: CACHE_STRATEGIES.STALE_WHILE_REVALIDATE,
        cacheDuration: 24 * 60 * 60 * 1000, // 24 hours
        maxSize: 5 * 1024 * 1024, // 5MB
        compression: true
    },
    'text/css': {
        strategy: CACHE_STRATEGIES.STALE_WHILE_REVALIDATE,
        cacheDuration: 24 * 60 * 60 * 1000, // 24 hours
        maxSize: 1024 * 1024, // 1MB
        compression: true
    }
};

// Install event
self.addEventListener('install', event => {
    console.log('S3 Cache Service Worker installing');
    
    event.waitUntil(
        Promise.all([
            // Skip waiting to activate immediately
            self.skipWaiting(),
            // Pre-cache important resources
            cacheEssentialResources()
        ])
    );
});

// Activate event
self.addEventListener('activate', event => {
    console.log('S3 Cache Service Worker activating');
    
    event.waitUntil(
        Promise.all([
            // Claim all clients immediately
            self.clients.claim(),
            // Clean up old caches
            cleanupOldCaches(),
            // Warm up cache with popular files
            warmUpCache()
        ])
    );
});

// Fetch event - handle all network requests
self.addEventListener('fetch', event => {
    const { request } = event;
    
    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }
    
    // Skip chrome-extension and other non-http requests
    if (!request.url.startsWith('http')) {
        return;
    }
    
    event.respondWith(handleFetchRequest(request));
});

// Background sync for offline support
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync-files') {
        event.waitUntil(syncOfflineFiles());
    }
});

// Handle fetch requests with appropriate caching strategy
async function handleFetchRequest(request) {
    const url = new URL(request.url);
    const fileType = getFileType(url.pathname);
    const config = FILE_CONFIGS[fileType] || FILE_CONFIGS['application/octet-stream'];
    
    try {
        switch (config.strategy) {
            case CACHE_STRATEGIES.CACHE_FIRST:
                return await cacheFirstStrategy(request, config);
                
            case CACHE_STRATEGIES.NETWORK_FIRST:
                return await networkFirstStrategy(request, config);
                
            case CACHE_STRATEGIES.STALE_WHILE_REVALIDATE:
                return await staleWhileRevalidateStrategy(request, config);
                
            case CACHE_STRATEGIES.NETWORK_ONLY:
                return await fetch(request);
                
            case CACHE_STRATEGIES.CACHE_ONLY:
                return await caches.match(request) || new Response('Not found', { status: 404 });
                
            default:
                return await networkFirstStrategy(request, config);
        }
    } catch (error) {
        console.error('Fetch request failed:', error);
        return await handleOfflineFallback(request, config);
    }
}

// Cache First Strategy
async function cacheFirstStrategy(request, config) {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
        // Update cache in background
        fetch(request).then(response => {
            if (response.ok) {
                caches.open(CACHE_NAME).then(cache => {
                    cache.put(request, response.clone());
                });
            }
        }).catch(() => {
            // Silently fail background update
        });
        
        return cachedResponse;
    }
    
    try {
        const response = await fetch(request);
        
        if (response.ok && isCacheable(response, config)) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, response.clone());
        }
        
        return response;
    } catch (error) {
        throw error;
    }
}

// Network First Strategy
async function networkFirstStrategy(request, config) {
    try {
        const response = await fetch(request);
        
        if (response.ok && isCacheable(response, config)) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(request, response.clone());
        }
        
        return response;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        
        if (cachedResponse) {
            return cachedResponse;
        }
        
        throw error;
    }
}

// Stale While Revalidate Strategy
async function staleWhileRevalidateStrategy(request, config) {
    const cache = await caches.open(CACHE_NAME);
    const cachedResponse = await cache.match(request);
    
    // Fetch network response in background
    const networkResponsePromise = fetch(request).then(response => {
        if (response.ok && isCacheable(response, config)) {
            cache.put(request, response.clone());
        }
        return response;
    }).catch(() => null);
    
    // Return cached response immediately if available
    if (cachedResponse) {
        return cachedResponse;
    }
    
    // Wait for network response if no cache
    return await networkResponsePromise || new Response('Network error', { status: 503 });
}

// Check if response is cacheable
function isCacheable(response, config) {
    if (!response.ok) return false;
    
    const contentType = response.headers.get('Content-Type');
    const contentLength = response.headers.get('Content-Length');
    
    // Check file size
    if (contentLength && parseInt(contentLength) > config.maxSize) {
        return false;
    }
    
    // Check file type
    if (contentType && !contentType.startsWith(config.fileType || '')) {
        return false;
    }
    
    return true;
}

// Get file type from URL path
function getFileType(pathname) {
    const extension = pathname.split('.').pop().toLowerCase();
    
    const typeMap = {
        'pdf': 'application/pdf',
        'jpg': 'image/jpeg',
        'jpeg': 'image/jpeg',
        'png': 'image/png',
        'gif': 'image/gif',
        'svg': 'image/svg+xml',
        'webp': 'image/webp',
        'html': 'text/html',
        'htm': 'text/html',
        'js': 'application/javascript',
        'css': 'text/css',
        'woff': 'font/woff',
        'woff2': 'font/woff2',
        'ttf': 'font/ttf'
    };
    
    return typeMap[extension] || 'application/octet-stream';
}

// Handle offline fallback
async function handleOfflineFallback(request, config) {
    const cachedResponse = await caches.match(request);
    
    if (cachedResponse) {
        return cachedResponse;
    }
    
    // Return offline page for HTML requests
    if (request.headers.get('Accept').includes('text/html')) {
        return new Response(getOfflinePage(), {
            headers: { 'Content-Type': 'text/html' }
        });
    }
    
    return new Response('Offline', { status: 503 });
}

// Cache essential resources
async function cacheEssentialResources() {
    const cache = await caches.open(CACHE_NAME);
    const essentialResources = [
        '/',
        '/css/s3-speed-optimization.css',
        '/js/s3-speed-optimization.js'
    ];
    
    try {
        await Promise.all(
            essentialResources.map(async (url) => {
                try {
                    const response = await fetch(url);
                    if (response.ok) {
                        await cache.put(url, response);
                    }
                } catch (error) {
                    console.warn('Failed to cache essential resource:', url);
                }
            })
        );
    } catch (error) {
        console.error('Failed to cache essential resources:', error);
    }
}

// Clean up old caches
async function cleanupOldCaches() {
    const cacheNames = await caches.keys();
    const validCacheNames = [CACHE_NAME, OFFLINE_CACHE_NAME];
    
    await Promise.all(
        cacheNames.map(cacheName => {
            if (!validCacheNames.includes(cacheName)) {
                return caches.delete(cacheName);
            }
        })
    );
}

// Warm up cache with popular files
async function warmUpCache() {
    try {
        // This would typically fetch from your API endpoint that returns popular files
        const response = await fetch('/api/popular-files');
        
        if (response.ok) {
            const popularFiles = await response.json();
            const cache = await caches.open(CACHE_NAME);
            
            await Promise.all(
                popularFiles.map(async (fileUrl) => {
                    try {
                        const response = await fetch(fileUrl);
                        if (response.ok) {
                            await cache.put(fileUrl, response);
                        }
                    } catch (error) {
                        console.warn('Failed to warm cache for file:', fileUrl);
                    }
                })
            );
        }
    } catch (error) {
        console.warn('Failed to warm cache:', error);
    }
}

// Sync offline files when connection is restored
async function syncOfflineFiles() {
    try {
        // This would sync any offline file operations
        console.log('Background sync: syncing offline files');
        
        // Send message to client about sync status
        const clients = await self.clients.matchAll();
        clients.forEach(client => {
            client.postMessage({
                type: 'BACKGROUND_SYNC',
                status: 'completed'
            });
        });
    } catch (error) {
        console.error('Background sync failed:', error);
    }
}

// Generate offline page
function getOfflinePage() {
    return `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Offline</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    text-align: center; 
                    padding: 50px; 
                    background: #f5f5f5; 
                }
                .offline-container {
                    max-width: 500px;
                    margin: 0 auto;
                    background: white;
                    padding: 40px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                .offline-icon {
                    font-size: 48px;
                    margin-bottom: 20px;
                }
                .retry-btn {
                    background: #007bff;
                    color: white;
                    border: none;
                    padding: 10px 20px;
                    border-radius: 4px;
                    cursor: pointer;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="offline-container">
                <div class="offline-icon">📱</div>
                <h1>You're Offline</h1>
                <p>Please check your internet connection and try again.</p>
                <button class="retry-btn" onclick="window.location.reload()">Retry</button>
            </div>
            
            <script>
                window.addEventListener('online', () => {
                    window.location.reload();
                });
            </script>
        </body>
        </html>
    `;
}

// Handle messages from main thread
self.addEventListener('message', event => {
    const { type, data } = event.data;
    
    switch (type) {
        case 'CACHE_FILE':
            cacheSpecificFile(data.url);
            break;
            
        case 'CLEAR_CACHE':
            clearSpecificCache(data.pattern);
            break;
            
        case 'GET_CACHE_STATUS':
            getCacheStatus().then(status => {
                event.ports[0].postMessage({ type: 'CACHE_STATUS', data: status });
            });
            break;
            
        default:
            console.log('Unknown message type:', type);
    }
});

// Cache a specific file
async function cacheSpecificFile(url) {
    try {
        const response = await fetch(url);
        if (response.ok) {
            const cache = await caches.open(CACHE_NAME);
            await cache.put(url, response);
            
            // Notify main thread
            const clients = await self.clients.matchAll();
            clients.forEach(client => {
                client.postMessage({
                    type: 'FILE_CACHED',
                    url: url,
                    success: true
                });
            });
        }
    } catch (error) {
        console.error('Failed to cache file:', error);
    }
}

// Clear specific cache patterns
async function clearSpecificCache(pattern) {
    const cacheNames = await caches.keys();
    
    await Promise.all(
        cacheNames.map(cacheName => {
            if (pattern.test(cacheName)) {
                return caches.delete(cacheName);
            }
        })
    );
}

// Get cache status
async function getCacheStatus() {
    const cache = await caches.open(CACHE_NAME);
    const keys = await cache.keys();
    
    return {
        totalFiles: keys.length,
        cacheSize: 'Unknown', // Would need to calculate actual size
        lastUpdated: new Date().toISOString()
    };
}

console.log('S3 Speed Cache Service Worker loaded');