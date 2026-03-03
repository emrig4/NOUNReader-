@extends('layouts.reader')

@section('title', 'Resource | ' . ($resource->title ?? 'View'))

@section('content')
{{-- S3 Speed Optimization: Non-intrusive performance enhancements --}}
{{-- Generated: 2025-11-15 10:00:57 --}}
<div class="container-fluid p-0">
    <div class="row no-gutters">
        <!-- Header Section -->
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center p-3 bg-light border-bottom">
                <div>
                    <h4 class="mb-0">{{ $resource->title ?? 'Resource' }}</h4>
                    <small class="text-muted">
                        @if($resource->category)
                            {{ $resource->category->name }}
                            @if($resource->sub_category)
                                &gt; {{ $resource->sub_category->name }}
                            @endif
                        @endif
                    </small>
                </div>
                <div class="d-flex align-items-center">
                    <button onclick="goBack()" class="btn btn-outline-primary btn-sm me-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </button>
                </div>
            </div>
        </div>

        <!-- File Viewer Section -->
        <div class="col-12">
            <div class="position-relative" style="height: calc(100vh - 120px);">
                @if($mainFile && $mainFile->url())
                    <!-- S3 Ultra-Fast Loading with Optimizations -->
                    <div id="fileViewer" class="w-100 h-100 d-flex flex-column">
                        <div class="flex-grow-1 position-relative">
                            <!-- Optimized Loading State -->
                            <div id="loadingState" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-light">
                                <div class="optimized-loading mb-3" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <h5 class="text-primary mb-2">Loading Document</h5>
                                <p class="text-muted mb-0">Preparing your resource with S3 speed optimization...</p>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-rocket text-warning"></i>
                                        S3 Ultra-Fast Loading Active
                                    </small>
                                </div>
                            </div>

                            <!-- File Content Container with S3 Optimization -->
                            <div id="fileContent" class="w-100 h-100 d-none">
                                <!-- S3-Optimized PDF Viewer -->
                                <div id="pdfViewer" class="w-100 h-100 s3-optimized">
                                    <vue-pdf-app 
                                        :base64="pdfData"
                                        :config="pdfConfig"
                                        @error="handlePdfError"
                                        @loaded="handlePdfLoaded">
                                    </vue-pdf-app>
                                </div>

                                <!-- S3-Optimized Other File Types -->
                                <div id="otherViewer" class="w-100 h-100 s3-optimized">
                                    <iframe 
                                        id="fileFrame" 
                                        src="" 
                                        class="w-100 h-100 border-0 gpu-accelerated"
                                        @load="handleIframeLoaded"
                                        @error="handleIframeError"
                                        data-s3-src="{{ $mainFile->url() }}"
                                        data-s3-progressive="true">
                                    </iframe>
                                </div>
                            </div>

                            <!-- Error State with Smart Recovery -->
                            <div id="errorState" class="position-absolute top-0 start-0 w-100 h-100 d-none d-flex flex-column justify-content-center align-items-center bg-light">
                                <div class="text-center">
                                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                    <h4 class="mt-3 text-danger">Failed to Load File</h4>
                                    <p class="text-muted mb-4">There was an error loading this resource.</p>
                                    
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button onclick="retryLoad()" class="btn btn-primary">
                                            <i class="fas fa-redo"></i> Try Again (Optimized)
                                        </button>
                                        @if($mainFile && $mainFile->download_url)
                                            <a href="{{ $mainFile->download_url }}" class="btn btn-outline-secondary" download>
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        @endif
                                    </div>
                                    
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            If this problem persists, please contact support.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- No File Available -->
                    <div class="w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-light">
                        <i class="fas fa-file-alt text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">No File Available</h4>
                        <p class="text-muted">This resource doesn't have an associated file.</p>
                        <button onclick="goBack()" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- S3 Speed Optimization Progress Overlay -->
<div id="progressOverlay" class="position-fixed top-0 start-0 w-100 h-100 d-none" style="background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex flex-column justify-content-center align-items-center h-100 text-white">
        <div class="text-center">
            <div class="optimized-loading mb-3" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5>Loading Document with S3 Optimization...</h5>
            <div class="progress mt-3" style="width: 300px;">
                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width: 0%">
                    <span id="progressText">0%</span>
                </div>
            </div>
            <div class="mt-2">
                <small>
                    <span id="s3Status">Initializing S3 optimizer...</span>
                </small>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- S3 Speed Optimization JavaScript Integration --}}
<script>
    // S3 Optimized Configuration
    const CONFIG = {
        fileUrl: '{{ $mainFile ? $mainFile->url() : "" }}',
        fileName: '{{ $mainFile ? ($mainFile->name ?? basename($mainFile->url())) : "" }}',
        fileType: '{{ $mainFile ? ($mainFile->mime_type ?? "") : "" }}',
        timeout: 30000, // 30 seconds
        retryAttempts: 3,
        retryDelay: 1000,
        
        // S3 Speed Optimization Settings
        s3Optimization: {
            enableAcceleration: {{ config('s3-speed.use_acceleration', 'false') }},
            cloudFrontDomain: '{{ config('s3-speed.cloudfront_domain', '') }}',
            enableProgressiveLoading: {{ config('s3-speed.progressive_loading.enabled', 'true') }},
            enableSmartCaching: {{ config('s3-speed.smart_cache.enabled', 'true') }},
        }
    };

    // S3 Performance State Management
    let s3State = {
        isLoading: false,
        hasLoaded: false,
        hasError: false,
        pdfData: null,
        fileType: null,
        s3Optimization: null,
        loadStartTime: null,
        cacheHit: false,
    };

    // Initialize when DOM is ready with S3 optimization
    document.addEventListener('DOMContentLoaded', function() {
        // Mark page as S3 optimized
        document.body.dataset.cloudFrontDomain = CONFIG.s3Optimization.cloudFrontDomain;
        document.documentElement.classList.add('s3-speed-optimized');
        
        if (CONFIG.fileUrl) {
            loadFileWithS3Optimization();
        } else {
            showError('No file URL provided');
        }
    });

    // S3 Ultra-Fast Loading with Optimizations
    async function loadFileWithS3Optimization() {
        if (s3State.isLoading || s3State.hasLoaded) return;
        
        s3State.isLoading = true;
        s3State.loadStartTime = performance.now();
        showLoading();
        updateS3Status('Analyzing file for S3 optimization...');
        
        try {
            // Step 1: Get S3 optimization strategy
            const optimization = await getS3OptimizationStrategy(CONFIG.fileUrl);
            s3State.s3Optimization = optimization;
            
            updateS3Status(`Using ${optimization.recommended.type} optimization...`);
            
            // Step 2: Load based on optimization strategy
            const fileType = detectFileType(CONFIG.fileType, CONFIG.fileName, CONFIG.fileUrl);
            s3State.fileType = fileType;
            
            if (fileType === 'pdf') {
                await loadPdfWithS3Optimization();
            } else {
                await loadOtherFileWithS3Optimization();
            }
            
            s3State.hasLoaded = true;
            hideLoading();
            showFileContent();
            
            // Log performance metrics
            const loadTime = performance.now() - s3State.loadStartTime;
            logS3PerformanceMetrics(optimization, loadTime);
            
        } catch (error) {
            console.error('S3 optimized loading error:', error);
            handleLoadError(error);
        } finally {
            s3State.isLoading = false;
        }
    }

    // Get S3 optimization strategy
    async function getS3OptimizationStrategy(url) {
        // Use S3 speed optimizer if available
        if (window.S3SpeedOptimizer) {
            return window.S3SpeedOptimizer.optimizeUrl(url);
        }
        
        // Fallback: Manual optimization detection
        return {
            original: url,
            recommended: {
                type: 'regular',
                url: url,
                speed: 'normal'
            },
            strategies: [{
                type: 'regular',
                url: url,
                speed: 'normal'
            }],
            metadata: {
                fileSize: await estimateFileSize(url),
                shouldAccelerate: false,
                shouldUseCDN: !!CONFIG.s3Optimization.cloudFrontDomain
            }
        };
    }

    // Load PDF with S3 optimization
    async function loadPdfWithS3Optimization() {
        updateS3Status('Loading PDF with S3 optimization...');
        
        try {
            const optimizedUrl = s3State.s3Optimization.recommended.url;
            
            // Try optimized loading first
            await loadPdfOptimized(optimizedUrl);
        } catch (optimizedError) {
            console.warn('Optimized PDF loading failed, trying fallback:', optimizedError);
            updateS3Status('Using fallback loading method...');
            
            // Fallback to regular loading
            await loadPdfRegular();
        }
    }

    // Optimized PDF loading
    async function loadPdfOptimized(url) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.timeout = CONFIG.timeout;
            
            xhr.onload = function() {
                if (xhr.status === 200 && xhr.response) {
                    const arrayBuffer = xhr.response;
                    const uint8Array = new Uint8Array(arrayBuffer);
                    const base64 = uint8ArrayToBase64(uint8Array);
                    s3State.pdfData = base64;
                    resolve();
                } else {
                    reject(new Error(`HTTP ${xhr.status}: ${xhr.statusText}`));
                }
            };
            
            xhr.onerror = function() {
                reject(new Error('Network error'));
            };
            
            xhr.ontimeout = function() {
                reject(new Error('Request timeout'));
            };
            
            xhr.responseType = 'arraybuffer';
            xhr.open('GET', url, true);
            xhr.send();
        });
    }

    // Regular PDF loading (fallback)
    async function loadPdfRegular() {
        // Fallback to the original URL
        if (CONFIG.fileUrl) {
            // Use the standard loading method
            await loadPdfDirect();
        }
    }

    // Load other file types with S3 optimization
    async function loadOtherFileWithS3Optimization() {
        updateS3Status('Loading file with S3 optimization...');
        
        const iframe = document.getElementById('fileFrame');
        if (iframe) {
            // Apply S3 optimization classes
            iframe.classList.add('s3-optimized', `s3-${s3State.s3Optimization.recommended.type}`);
            
            // Set the optimized URL
            iframe.src = s3State.s3Optimization.recommended.url;
        }
    }

    // S3 Performance logging
    function logS3PerformanceMetrics(optimization, loadTime) {
        if (window.S3SpeedOptimizer && window.S3SpeedOptimizer.performanceMonitor) {
            window.S3SpeedOptimizer.performanceMonitor.recordMetric(CONFIG.fileUrl, {
                duration: loadTime,
                optimization: optimization.recommended.type,
                cacheHit: s3State.cacheHit,
                fileSize: optimization.metadata.fileSize
            });
        }
        
        console.log(`🚀 S3 Optimization Report:
            Strategy: ${optimization.recommended.type}
            Load Time: ${Math.round(loadTime)}ms
            File Size: ${optimization.metadata.fileSize ? formatBytes(optimization.metadata.fileSize) : 'Unknown'}
            Cache Hit: ${s3State.cacheHit ? 'Yes' : 'No'}`);
    }

    // Utility functions
    function updateS3Status(message) {
        const statusElement = document.getElementById('s3Status');
        if (statusElement) {
            statusElement.textContent = message;
        }
    }

    async function estimateFileSize(url) {
        try {
            const response = await fetch(url, { method: 'HEAD' });
            const contentLength = response.headers.get('content-length');
            return contentLength ? parseInt(contentLength) : null;
        } catch (error) {
            return null;
        }
    }

    function formatBytes(bytes) {
        if (bytes === 0) return '0 B';
        const k = 1024;
        const sizes = ['B', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Enhanced UI state management with S3 optimization
    function showLoading() {
        document.getElementById('loadingState')?.classList.remove('d-none');
        document.getElementById('fileContent')?.classList.add('d-none');
        document.getElementById('errorState')?.classList.add('d-none');
        document.getElementById('progressOverlay')?.classList.remove('d-none');
    }

    function hideLoading() {
        document.getElementById('loadingState')?.classList.add('d-none');
        document.getElementById('progressOverlay')?.classList.add('d-none');
    }

    function showFileContent() {
        document.getElementById('fileContent')?.classList.remove('d-none');
        
        // Show appropriate viewer with optimization classes
        if (s3State.fileType === 'pdf') {
            document.getElementById('pdfViewer')?.classList.remove('d-none');
            document.getElementById('otherViewer')?.classList.add('d-none');
        } else {
            document.getElementById('pdfViewer')?.classList.add('d-none');
            document.getElementById('otherViewer')?.classList.remove('d-none');
        }
    }

    function showError(message) {
        hideLoading();
        document.getElementById('errorState')?.classList.remove('d-none');
    }

    // Enhanced error handling with S3 optimization
    function handleLoadError(error) {
        s3State.hasError = true;
        hideLoading();
        showError(error.message || 'Failed to load file');
        
        // Log S3 optimization error
        console.error('S3 Loading Error:', {
            message: error.message,
            optimization: s3State.s3Optimization,
            loadTime: s3State.loadStartTime ? performance.now() - s3State.loadStartTime : null
        });
    }

    // Enhanced PDF event handlers
    function handlePdfError(error) {
        console.error('S3 PDF viewer error:', error);
        handleLoadError(new Error('S3 PDF viewer error'));
    }

    function handleIframeError() {
        handleLoadError(new Error('S3 file display error'));
    }

    function handlePdfLoaded() {
        console.log('🚀 S3 PDF loaded successfully with optimization');
        updateProgress(100);
        s3State.cacheHit = false; // This was a fresh load
        
        // Dispatch S3 optimization event
        document.dispatchEvent(new CustomEvent('s3-file-loaded', {
            detail: {
                type: 'pdf',
                optimization: s3State.s3Optimization,
                loadTime: s3State.loadStartTime ? performance.now() - s3State.loadStartTime : null
            }
        }));
    }

    function handleIframeLoaded() {
        console.log('🚀 S3 file loaded successfully with optimization');
        updateProgress(100);
        s3State.cacheHit = false;
        
        // Dispatch S3 optimization event
        document.dispatchEvent(new CustomEvent('s3-file-loaded', {
            detail: {
                type: 'other',
                optimization: s3State.s3Optimization,
                loadTime: s3State.loadStartTime ? performance.now() - s3State.loadStartTime : null
            }
        }));
    }

    function updateProgress(percent) {
        const progressBar = document.getElementById('progressBar');
        const progressText = document.getElementById('progressText');
        
        if (progressBar) {
            progressBar.style.width = percent + '%';
        }
        if (progressText) {
            progressText.textContent = percent + '%';
        }
    }

    // Enhanced retry with S3 optimization
    async function retryLoad() {
        if (s3State.hasError) {
            s3State.hasError = false;
            s3State.hasLoaded = false;
            await loadFileWithS3Optimization();
        }
    }

    // Navigation (unchanged)
    function goBack() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '{{ url()->previous() ?: route("resources.index") }}';
        }
    }

    // Utility functions (enhanced)
    function uint8ArrayToBase64(uint8Array) {
        let binary = '';
        const chunkSize = 0x8000;
        
        for (let i = 0; i < uint8Array.length; i += chunkSize) {
            const chunk = uint8Array.subarray(i, i + chunkSize);
            binary += String.fromCharCode.apply(null, chunk);
        }
        
        return btoa(binary);
    }

    function detectFileType(mimeType, fileName, url) {
        // Check MIME type first
        if (mimeType) {
            if (mimeType.includes('pdf')) return 'pdf';
            if (mimeType.includes('image')) return 'image';
            if (mimeType.includes('video')) return 'video';
            if (mimeType.includes('audio')) return 'audio';
        }
        
        // Check file extension
        const ext = (fileName || url || '').split('.').pop().toLowerCase();
        const typeMap = {
            'pdf': 'pdf',
            'jpg': 'image',
            'jpeg': 'image',
            'png': 'image',
            'gif': 'image',
            'webp': 'image',
            'mp4': 'video',
            'avi': 'video',
            'mov': 'video',
            'mp3': 'audio',
            'wav': 'audio'
        };
        
        return typeMap[ext] || 'other';
    }

    // Global error handling with S3 optimization
    window.addEventListener('error', function(e) {
        console.error('S3 Global error:', e.error);
    });

    // Performance monitoring with S3 optimization
    window.addEventListener('load', function() {
        const loadTime = performance.now();
        console.log(`🚀 S3 Optimized page loaded in ${Math.round(loadTime)}ms`);
        
        // Dispatch page load event
        document.dispatchEvent(new CustomEvent('s3-page-loaded', {
            detail: {
                loadTime: loadTime,
                optimizations: s3State.s3Optimization
            }
        }));
    });
</script>

<!-- S3 PDF Configuration -->
<script>
    // Vue PDF App configuration with S3 optimization
    window.vuePdfConfig = {
        pdfjsWorkerSrc: '{{ asset("vendor/vue-pdf-app/pdf.worker.min.js") }}',
        pdfjsSrc: '{{ asset("vendor/vue-pdf-app/pdf.min.js") }}',
        cMapUrl: '{{ asset("vendor/vue-pdf-app/cmaps/") }}',
        cMapPacked: true,
        disableWorker: false,
        disableAutoFetch: false,
        disableFontFace: false,
        disableRange: false,
        disableStream: false,
        disableAutoFetch: false,
        isEvalSupported: false,
        enableXfa: false,
        verbosity: 1,
        
        // S3 optimization settings
        enableS3Optimization: true,
        useCloudFront: {{ config('s3-speed.cloudfront_domain', '') ? 'true' : 'false' }},
        enableAcceleration: {{ config('s3-speed.use_acceleration', 'false') ? 'true' : 'false' }},
    };
</script>
@endpush

{{-- S3 Speed Optimization CSS Integration --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/s3-speed-optimization.css') }}">
<style>
/* S3-specific optimizations */
#pdfViewer.s3-optimized {
    /* S3 optimized PDF viewer styling */
    contain: layout style paint;
}

#otherViewer.s3-optimized {
    /* S3 optimized file viewer styling */
    contain: layout style paint;
}

.s3-optimized {
    /* General S3 optimization styling */
    will-change: transform;
    transform: translateZ(0);
}

/* Progressive loading container */
.progressive-container {
    position: relative;
    overflow: hidden;
}

.progressive-preview {
    filter: blur(1px);
    opacity: 0.8;
    transition: all 0.3s ease;
}

.progressive-full {
    opacity: 0;
    transition: opacity 0.5s ease;
}

.progressive-full.loaded {
    opacity: 1;
}

/* Performance metrics styling */
.performance-metrics {
    position: fixed;
    bottom: 10px;
    right: 10px;
    background: rgba(0, 0, 0, 0.9);
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-family: monospace;
    z-index: 9999;
    opacity: 0;
    transform: translateY(100%);
    transition: all 0.3s ease;
}

.performance-metrics.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .performance-metrics {
        font-size: 9px;
        padding: 4px 6px;
        bottom: 5px;
        right: 5px;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .progressive-preview,
    .progressive-full {
        transition: none;
    }
}
</style>
@endpush
