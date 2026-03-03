<template>
  <div class="pdf-viewer-wrapper">
    <!-- Loading State -->
    <div v-if="isLoading" class="pdf-loading-container">
      <div class="spinner-ring"></div>
      <p>Loading document...</p>
    </div>

    <!-- Error State -->
    <div v-else-if="loadError" class="pdf-error-container">
      <p>Failed to load document</p>
      <button @click="retryLoad" class="retry-btn">Retry</button>
    </div>

    <!-- PDF Viewer -->
    <vue-pdf-app v-else theme="air" :pdf="pdfData" @after-created="createdHandler" @open="openHandler" @pages-rendered="renderedHandler" :config="config">
      <template #toolbar-right-prepend>
        <button onclick="openFullscreen()" class="toolbarButton presentationMode vue-pdf-app-icon presentation-mode" id="fullscreen-mode" type="button">
          <span data-l10n-id="presentation_mode_label">Fullscreen Mode</span>
        </button>
      </template>
    </vue-pdf-app>
  </div>
</template>

<script>
import "../vendor/vue-pdf-app/dist/icons/main.css";
import VuePdfApp from '../vendor/vue-pdf-app'

export default {
  name: "VuePdfAppComponent",
  props: {
    pdfsrc: {
      type: [Object, String],
      required: true,
      default: ''
    },

    base64: {
      type: [Object, String],
      default: ''
    },

    preview_limit: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      pdfData: '',
      isLoading: true,
      loadError: false,
      config: {
        toolbar: {
          toolbarViewerRight: {
            openFile: false,
            print: false,
            download: false,
            presentationMode: false
          },
          secondaryToolbar: false,
        },
        secondaryToolbar: {
          openFile: false,
          print: false,
          download: false,
          presentationMode: false,

          secondaryPresentationMode: false,
          secondaryDownload: false,
          secondaryOpenFile: false,
          secondaryPrint: false,
        }
      }
    }
  },
  components: {
    VuePdfApp: window['vue-pdf-app']
  },

  async mounted(){
    // Priority 1: Load from URL (fast - direct browser download)
    if(this.pdfsrc && this.pdfsrc.length > 0) {
      await this.loadFromUrl(this.pdfsrc);
    }
    // Priority 2: Fallback to base64 (backward compatibility)
    else if(this.base64 && this.base64.length > 0) {
      try {
        this.pdfData = this.base64ToArrayBuffer(this.base64);
        this.isLoading = false;
      } catch(e) {
        console.error('Base64 decode failed:', e);
        this.loadError = true;
        this.isLoading = false;
      }
    } else {
      this.loadError = true;
      this.isLoading = false;
    }
  },

  methods: {
    async loadFromUrl(url) {
      try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 60000); // 60s timeout
        
        const response = await fetch(url, { signal: controller.signal });
        clearTimeout(timeoutId);
        
        if(!response.ok) throw new Error('Network response was not ok');
        
        this.pdfData = await response.arrayBuffer();
        this.isLoading = false;
      } catch(e) {
        console.error('URL fetch failed:', e);
        // Fallback to base64 if URL fails
        if(this.base64 && this.base64.length > 0) {
          try {
            this.pdfData = this.base64ToArrayBuffer(this.base64);
            this.isLoading = false;
          } catch(e2) {
            this.loadError = true;
            this.isLoading = false;
          }
        } else {
          this.loadError = true;
          this.isLoading = false;
        }
      }
    },

    retryLoad() {
      this.isLoading = true;
      this.loadError = false;
      if(this.pdfsrc && this.pdfsrc.length > 0) {
        this.loadFromUrl(this.pdfsrc);
      } else if(this.base64 && this.base64.length > 0) {
        try {
          this.pdfData = this.base64ToArrayBuffer(this.base64);
          this.isLoading = false;
        } catch(e) {
          this.loadError = true;
          this.isLoading = false;
        }
      }
    },

    base64ToArrayBuffer(base64) {
      var binary_string = window.atob(base64);
      var len = binary_string.length;
      var bytes = new Uint8Array(len);
      for (var i = 0; i < len; i++) {
        bytes[i] = binary_string.charCodeAt(i);
      }
      return bytes.buffer;
    },

    async createdHandler(pdfApp){
      console.log(pdfApp)
    },

    async renderedHandler(pdfApp){
      let pdfViewer = await pdfApp.pdfViewer
      let viewer = pdfViewer.viewer
      let pages = pdfViewer._pages
      let page1 = pdfApp.pdfViewer._pages[0]

      if(this.preview_limit){
        // add overlay
        pdfApp.pdfViewer._pages.forEach((page, i) => {
          if(i >= this.preview_limit){
            this.addOverlay(pdfApp, i)
          }
        });

        // return from page1 - 4 
        pdfApp.pdfViewer._pages = pages.slice(0, (this.preview_limit+4))
      }else{
        pdfApp.pdfViewer._pages = pages
      }
    },

    async openHandler(pdfApp) {
      // window._pdfApp = pdfApp;
    },

    addOverlay(pdfApp, pageNumber){
      var pageNumber = pageNumber;
      var pdfRect = [0,0,140,150];

      var pageView = pdfApp.pdfViewer.getPageView(pageNumber - 1);
      var screenRect = pageView.viewport.convertToViewportRectangle(pdfRect);

      var x = Math.min(screenRect[0], screenRect[2]), width = Math.abs(screenRect[0] - screenRect[2]);
      var y = Math.min(screenRect[1], screenRect[3]), height = Math.abs(screenRect[1] - screenRect[3]);

      // note: needs to be done in the 'pagerendered' event
      var overlayDiv = document.createElement('div');
      overlayDiv.setAttribute('style', 'z-index: 3000; background-color: whitesmoke;position:absolute;' +
        'left:' + x + 'px;top:' + '1' + 'px;width:' + '100' + '%;height:' + '100' + '%; display: flex; align-items: center;');
      overlayDiv.innerHTML = '<div class="ereaders-preview-text"><span>END OF PREVIEW</span><p>Subscribe to continue reading and read thousands more..</p><div class="clearfix"></div><a href="/pricings" class="eraders-search-btn" style="padding: 5px 2px; margin: auto">Click here to Subscribe <i class="icon ereaders-right-arrow"></i></a></div>'
      pageView.div.appendChild(overlayDiv);
    },
  },
};
</script>

<style type="text/css">
.pdf-viewer-wrapper {
  width: 100%;
  height: 100%;
  position: relative;
}

.pdf-loading-container,
.pdf-error-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 400px;
  background: #f8f9fa;
  border-radius: 8px;
}

.pdf-loading-container p,
.pdf-error-container p {
  margin-top: 15px;
  color: #666;
  font-size: 14px;
}

.spinner-ring {
  width: 40px;
  height: 40px;
  border: 4px solid #e0e0e0;
  border-top-color: #3498db;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.retry-btn {
  margin-top: 10px;
  padding: 8px 20px;
  background: #3498db;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 14px;
}

.retry-btn:hover {
  background: #2980b9;
}

.pdf-app.air {
    --pdf-app-background-color: none;
    --pdf-sidebar-content-color: none;
     --pdf-toolbar-sidebar-color: none; 
     --pdf-toolbar-color: white; 
    --pdf-loading-bar-color: #606c88;
    --pdf-loading-bar-secondary-color: #11ece5;
    --pdf-find-results-count-color: #d9d9d9;
    --pdf-find-results-count-font-color: #525252;
    --pdf-find-message-font-color: #a6b7d0;
    --pdf-not-found-color: #f66;
    --pdf-split-toolbar-button-separator-color: #fff;
    --pdf-toolbar-font-color: gray;  /* #d9d9d9; */
    --pdf-button-hover-font-color: #00aff0;
    --pdf-button-toggled-color: rgb(0 0 0 / 10%);
    --pdf-horizontal-toolbar-separator-color: #fff;
    --pdf-input-color: #606c88;
    --pdf-input-font-color: #d9d9d9;
    --pdf-find-input-placeholder-font-color: #11ece5;
    --pdf-thumbnail-selection-ring-color: hsla(0,0%,100%,0.15);
    --pdf-thumbnail-selection-ring-selected-color: hsla(0,0%,100%,0.3);
    --pdf-error-wrapper-color: #f55;
    --pdf-error-more-info-color: #d9d9d9;
    --pdf-error-more-info-font-color: #000;
    --pdf-overlay-container-color: rgba(0,0,0,0.2);
    --pdf-overlay-container-dialog-color: #24364e;
    --pdf-overlay-container-dialog-font-color: #d9d9d9;
    --pdf-overlay-container-dialog-separator-color: #fff;
    --pdf-dialog-button-font-color: #d9d9d9;
    --pdf-dialog-button-color: #606c88;
}

.pdf-app .pdfViewer .page {
    direction: ltr;
    width: 816px;
    height: 1056px;
    margin: 1px auto 10px auto !important;
    position: relative;
    overflow: visible;
     border: none !important; 
    background-clip: content-box;
    background-color: #fff;
    box-shadow: 0px -2px 5px 0px;
}

.pdf-app .toolbarField.pageNumber {
    -moz-appearance: textfield;
    min-width: 36px;
    text-align: right;
    width: 60px;
    min-height: 25px;
    background: none !important;
}
</style>

