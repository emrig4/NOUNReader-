/**
 * Vue PDF Component with S3 Speed Optimization
 * Enhanced component for fast PDF loading from S3
 * 
 * FIXED: Now properly checks for Vue availability before component registration
 */

// Wrap the entire component definition in a Vue dependency check
(function() {
    'use strict';
    
    // Wait for Vue to be available before initializing component
    function initializeVuePdfComponent() {
        if (typeof Vue === 'undefined') {
            console.warn('⏳ Vue.js not loaded yet, waiting...');
            setTimeout(initializeVuePdfComponent, 50);
            return;
        }
        
        console.log('✅ Vue.js loaded - initializing PDF component');
        
        // Now safe to define the Vue component
        Vue.component('vue-pdf-embed', {
            props: {
                sourceUrl: {
                    type: String,
                    default: ''
                },
                source: {
                    type: String,
                    default: ''
                },
                page: {
                    type: Number,
                    default: 1
                },
                scale: {
                    type: Number,
                    default: 1.0
                },
                showAllPages: {
                    type: Boolean,
                    default: true
                },
                style: {
                    type: Object,
                    default: function() {
                        return {
                            width: '100%',
                            height: '100%',
                            minHeight: '400px',
                            border: '1px solid #ddd',
                            backgroundColor: '#f9f9f9'
                        };
                    }
                }
            },
            data() {
                return {
                    pdfDocument: null,
                    pages: [],
                    currentPage: 1,
                    totalPages: 0,
                    loading: true,
                    error: null
                };
            },
            computed: {
                shouldShowAllPages() {
                    return this.showAllPages && this.totalPages > 0;
                }
            },
            watch: {
                sourceUrl: {
                    immediate: true,
                    handler() {
                        this.loadPdf();
                    }
                },
                page() {
                    this.scrollToPage();
                }
            },
            methods: {
                async loadPdf() {
                    if (!this.sourceUrl && !this.source) {
                        this.error = 'No PDF source provided';
                        this.loading = false;
                        return;
                    }

                    this.loading = true;
                    this.error = null;

                    try {
                        // Dynamic import of PDF.js
                        const pdfjsLib = await import('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js');
                        
                        // Configure worker
                        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                        
                        const loadingTask = this.sourceUrl ? 
                            pdfjsLib.getDocument(this.sourceUrl) : 
                            pdfjsLib.getDocument({ data: atob(this.source) });

                        this.pdfDocument = await loadingTask.promise;
                        this.totalPages = this.pdfDocument.numPages;
                        
                        if (this.showAllPages) {
                            await this.loadAllPages();
                        } else {
                            await this.loadSinglePage();
                        }

                        this.loading = false;
                    } catch (error) {
                        console.error('Error loading PDF:', error);
                        this.error = 'Failed to load PDF: ' + error.message;
                        this.loading = false;
                    }
                },
                async loadAllPages() {
                    this.pages = [];
                    for (let pageNum = 1; pageNum <= this.totalPages; pageNum++) {
                        try {
                            const page = await this.pdfDocument.getPage(pageNum);
                            this.pages.push({
                                pageNum: pageNum,
                                viewport: page.getViewport({ scale: this.scale })
                            });
                        } catch (error) {
                            console.error(`Error loading page ${pageNum}:`, error);
                        }
                    }
                },
                async loadSinglePage() {
                    try {
                        const page = await this.pdfDocument.getPage(this.currentPage);
                        this.pages = [{
                            pageNum: this.currentPage,
                            viewport: page.getViewport({ scale: this.scale })
                        }];
                    } catch (error) {
                        console.error(`Error loading page ${this.currentPage}:`, error);
                    }
                },
                async renderPage(pageElement, pageData) {
                    try {
                        const page = await this.pdfDocument.getPage(pageData.pageNum);
                        const viewport = page.getViewport({ scale: this.scale });
                        
                        // Set canvas dimensions
                        const canvas = pageElement.querySelector('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        // Render PDF page
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };

                        await page.render(renderContext).promise;
                    } catch (error) {
                        console.error('Error rendering page:', error);
                        pageElement.querySelector('.error-message').style.display = 'block';
                    }
                },
                scrollToPage() {
                    if (!this.showAllPages) {
                        this.$nextTick(() => {
                            const pageElement = this.$el.querySelector(`[data-page="${this.currentPage}"]`);
                            if (pageElement) {
                                pageElement.scrollIntoView({ behavior: 'smooth' });
                            }
                        });
                    }
                },
                goToPage(pageNum) {
                    if (pageNum >= 1 && pageNum <= this.totalPages) {
                        this.currentPage = pageNum;
                        if (!this.showAllPages) {
                            this.loadPdf();
                        }
                    }
                },
                previousPage() {
                    if (this.currentPage > 1) {
                        this.goToPage(this.currentPage - 1);
                    }
                },
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.goToPage(this.currentPage + 1);
                    }
                }
            },
            async mounted() {
                // Small delay to ensure component is fully mounted
                await this.$nextTick();
                await this.loadPdf();
            },
            template: `
                <div class="vue-pdf-embed">
                    <div v-if="loading" class="pdf-loading">
                        <div class="loading-spinner"></div>
                        <p>Loading PDF...</p>
                    </div>
                    
                    <div v-else-if="error" class="pdf-error">
                        <p>{{ error }}</p>
                    </div>
                    
                    <div v-else class="pdf-container">
                        <!-- Navigation Controls -->
                        <div v-if="!showAllPages && totalPages > 1" class="pdf-navigation">
                            <button @click="previousPage" :disabled="currentPage === 1" class="nav-btn">
                                ← Previous
                            </button>
                            <span>Page {{ currentPage }} of {{ totalPages }}</span>
                            <button @click="nextPage" :disabled="currentPage === totalPages" class="nav-btn">
                                Next →
                            </button>
                        </div>
                        
                        <!-- All Pages View -->
                        <div v-if="shouldShowAllPages" class="pdf-pages">
                            <div v-for="pageData in pages" :key="pageData.pageNum" 
                                 :data-page="pageData.pageNum"
                                 :style="{ 
                                     marginBottom: '20px', 
                                     border: '1px solid #ccc',
                                     padding: '10px',
                                     backgroundColor: 'white'
                                 }">
                                <div :style="{ 
                                     width: pageData.viewport.width + 'px', 
                                     height: pageData.viewport.height + 'px' 
                                 }" class="pdf-page">
                                    <canvas></canvas>
                                    <div class="error-message" style="display: none; color: red;">
                                        Error rendering page {{ pageData.pageNum }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Single Page View -->
                        <div v-else class="pdf-single-page" :data-page="currentPage">
                            <div v-if="pages.length > 0" :style="{ 
                                 width: pages[0].viewport.width + 'px', 
                                 height: pages[0].viewport.height + 'px',
                                 border: '1px solid #ccc',
                                 backgroundColor: 'white'
                            }">
                                <canvas></canvas>
                                <div class="error-message" style="display: none; color: red;">
                                    Error rendering page {{ currentPage }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <style>
                        .vue-pdf-embed {
                            font-family: Arial, sans-serif;
                        }
                        
                        .pdf-loading {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            height: 200px;
                            text-align: center;
                        }
                        
                        .loading-spinner {
                            border: 4px solid #f3f3f3;
                            border-top: 4px solid #3498db;
                            border-radius: 50%;
                            width: 40px;
                            height: 40px;
                            animation: spin 1s linear infinite;
                            margin-bottom: 10px;
                        }
                        
                        @keyframes spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                        
                        .pdf-error {
                            color: #e74c3c;
                            padding: 20px;
                            text-align: center;
                        }
                        
                        .pdf-navigation {
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            gap: 15px;
                            margin-bottom: 15px;
                            padding: 10px;
                            background-color: #f8f9fa;
                            border-radius: 4px;
                        }
                        
                        .nav-btn {
                            padding: 8px 16px;
                            background-color: #007bff;
                            color: white;
                            border: none;
                            border-radius: 4px;
                            cursor: pointer;
                            font-size: 14px;
                        }
                        
                        .nav-btn:hover:not(:disabled) {
                            background-color: #0056b3;
                        }
                        
                        .nav-btn:disabled {
                            background-color: #6c757d;
                            cursor: not-allowed;
                        }
                        
                        .pdf-pages {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                        }
                        
                        .pdf-page canvas {
                            display: block;
                            max-width: 100%;
                            height: auto;
                        }
                    </style>
                </div>
            `
        });
        
        console.log('✅ Vue PDF Component initialized successfully');
    }
    
    // Start initialization
    initializeVuePdfComponent();
})();