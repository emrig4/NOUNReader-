<!-- filepath: resources/views/resource/partials/download-modal.blade.php -->
<div class="fixed z-10 inset-0 overflow-y-auto" id="downloadresourcemodal" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

        <!-- Centering trick for modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="">
                    <div class="flex justify-between items-center space-x-5">
                        <div class="flex items-center space-x-5">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full sm:mx-0 sm:h-10 sm:w-10" style="background: whitesmoke">
                                <svg class="h-6 w-6" style="color: #8d94a0;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="122.88px" height="122.878px" viewBox="0 0 122.88 122.878" enable-background="new 0 0 122.88 122.878" xml:space="preserve">
                                    <g>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M101.589,14.7l8.818,8.819c2.321,2.321,2.321,6.118,0,8.439l-7.101,7.101 c1.959,3.658,3.454,7.601,4.405,11.752h9.199c3.283,0,5.969,2.686,5.969,5.968V69.25c0,3.283-2.686,5.969-5.969,5.969h-10.039 c-1.231,4.063-2.992,7.896-5.204,11.418l6.512,6.51c2.321,2.323,2.321,6.12,0,8.44l-8.818,8.819c-2.321,2.32-6.119,2.32-8.439,0 l-7.102-7.102c-3.657,1.96-7.601,3.456-11.753,4.406v9.199c0,3.282-2.685,5.968-5.968,5.968H53.629 c-3.283,0-5.969-2.686-5.969-5.968v-10.039c-4.063-1.232-7.896-2.993-11.417-5.205l-6.511,6.512c-2.323,2.321-6.12,2.321-8.441,0 l-8.818-8.818c-2.321-2.321-2.321-6.118,0-8.439l7.102-7.102c-1.96-3.657-3.456-7.6-4.405-11.751H5.968 C2.686,72.067,0,69.382,0,66.099V53.628c0-3.283,2.686-5.968,5.968-5.968h10.039c1.232-4.063,2.993-7.896,5.204-11.418l-6.511-6.51 c-2.321-2.322-2.321-6.12,0-8.44l8.819-8.819c2.321-2.321,6.118-2.321,8.439,0l7.101,7.101c3.658-1.96,7.601-3.456,11.753-4.406 V5.969C50.812,2.686,53.498,0,56.78,0h12.471c3.282,0,5.968,2.686,5.968,5.969v10.036c4.064,1.231,7.898,2.992,11.422,5.204 l6.507-6.509C95.471,12.379,99.268,12.379,101.589,14.7L101.589,14.7z M61.44,36.92c13.54,0,24.519,10.98,24.519,24.519 c0,13.538-10.979,24.519-24.519,24.519c-13.539,0-24.519-10.98-24.519-24.519C36.921,47.9,47.901,36.92,61.44,36.92L61.44,36.92z" />
                                    </g>
                                </svg>
                            </div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Download project</h3>
                        </div>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="mt-8 text-center sm:ml-4 sm:text-left">
                        <div class="flex space-x-2 w-full mt-4">
                            <p>Download this project from your credit unit or top-up to unlock, access and download more.</p>
                        </div>
                    </div>

                    <!-- ✅ DOWNLOAD PROGRESS BAR (Mobile-friendly) -->
                    <div id="downloadProgress" class="hidden mt-6 w-full px-4 py-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">Downloading...</span>
                            <span class="text-sm font-bold" id="progressPercent" style="color: #23A455;">0%</span>
                        </div>
                        <div class="w-full bg-gray-300 rounded-full h-4 overflow-hidden">
                            <div id="progressBar" class="h-4 rounded-full transition-all duration-300" style="width: 0%; background: linear-gradient(90deg, #23A455 0%, #1a7f3f 100%);"></div>
                        </div>
                        <div class="mt-3 text-center">
                            <p class="text-xs text-gray-600 font-semibold">
                                <span id="downloadedSize">0</span> MB / <span id="totalSize">0</span> MB
                            </p>
                        </div>

                        <!-- ✅ COMPLETION MESSAGE (Appears below progress) -->
                        <div id="completionMessage" class="hidden mt-4 w-full">
                            <div class="rounded-lg p-4" style="background: #d1fae5; border: 2px solid #23A455;">
                                <div class="flex items-center space-x-3">
                                    <span class="text-3xl">✅</span>
                                    <div class="flex-1">
                                        <p class="text-base font-bold" style="color: #23A455;">Download Complete!</p>
                                        <p class="text-sm" style="color: #1a7f3f; margin-top: 4px;">Check your downloads folder for the file.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="payWithCredit()" id="creditPayBtn" class="ereaders-detail-btn w-full">
                    Pay with Credit <br>
                    <span class="text-black text-xs"> {{ round( ranc_equivalent($resource->price, $resource->currency ), 2) }} unit</span>
                </button>
            </div>

            <!-- ✅ Buy Credit Button -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <a href="/pricings" class="w-full inline-flex items-center justify-center px-4 py-3 border-2 text-sm font-medium rounded-lg transition-all duration-200 ease-in-out" style="border-color: #23A455; color: #23A455; background-color: rgba(35, 164, 85, 0.05);" onmouseover="this.style.backgroundColor='rgba(35, 164, 85, 0.1)'" onmouseout="this.style.backgroundColor='rgba(35, 164, 85, 0.05)'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Buy More Credits
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Paystack -->
@push('js')
    <style>
        /* ✅ BRAND COLOR FROM HEADER */
        :root {
            --brand-green: #23A455;
        }

        /* ✅ MOBILE-OPTIMIZED NOTIFICATIONS - BOTTOM CENTER */
        .download-notification {
            z-index: 999999 !important;
            position: fixed !important;
            pointer-events: auto !important;
            bottom: 80px !important;
            left: 50% !important;
            right: auto !important;
            top: auto !important;
            transform: translateX(-50%) translateY(120px) scale(0.9) !important;
            max-width: calc(100vw - 30px) !important;
            width: calc(100vw - 30px) !important;
        }
        
        .download-notification.show {
            transform: translateX(-50%) translateY(0) scale(1) !important;
        }

        /* Desktop positioning */
        @media (min-width: 641px) {
            .download-notification {
                top: 20px !important;
                bottom: auto !important;
                left: auto !important;
                right: 20px !important;
                transform: translateX(400px) scale(0.8) !important;
                max-width: 450px !important;
                width: auto !important;
            }
            
            .download-notification.show {
                transform: translateX(0) scale(1) !important;
            }
        }

        /* ✅ DOWNLOAD PROGRESS ANIMATION */
        @keyframes pulse-download {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .downloading-state {
            animation: pulse-download 1s ease-in-out infinite;
        }

        /* ✅ SUCCESS BOUNCE ANIMATION */
        @keyframes bounce-in {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #completionMessage {
            animation: bounce-in 0.5s ease-out;
        }
    </style>

    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        var public_key = '<?php echo env('PAYSTACK_PUBLIC_KEY') ; ?>';
        
        // ✅ MOBILE-FRIENDLY: Download file with real-time progress
        async function payWithCredit(){
            const btn = document.getElementById('creditPayBtn');
            const progressDiv = document.getElementById('downloadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const downloadedSize = document.getElementById('downloadedSize');
            const totalSize = document.getElementById('totalSize');
            const completionMessage = document.getElementById('completionMessage');
            
            // Disable button and show progress
            btn.disabled = true;
            btn.classList.add('downloading-state');
            btn.innerHTML = '<span class="text-center">⏳ Preparing download...</span>';
            progressDiv.classList.remove('hidden');
            completionMessage.classList.add('hidden');

            // ✅ MOBILE-FIXED: Use form submission instead of fetch to properly handle redirects
            // This ensures credit check errors are properly shown and downloads are not bypassed
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '<?php echo route('resources.download', $resource->slug); ?>';
            form.style.display = 'none';

            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }

            document.body.appendChild(form);

            // Close modal first
            $('#downloadresourcemodal').modal('hide');

            // Clean up modal backdrop
            setTimeout(() => {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            }, 300);

            // Submit form after modal closes
            // This will trigger proper redirect with error messages if credits are insufficient
            setTimeout(() => {
                form.submit();

                // Show notification that download is starting
                showNotification('📥 Download started! Check your downloads folder.', 'success');

                // Reset button after a delay
                setTimeout(() => {
                    btn.disabled = false;
                    btn.classList.remove('downloading-state');
                    btn.innerHTML = 'Pay with Credit <br><span class="text-black text-xs"> {{ round( ranc_equivalent($resource->price, $resource->currency ), 2) }} unit</span>';
                    btn.style.backgroundColor = '';
                    btn.style.color = '';
                    progressDiv.classList.add('hidden');
                    completionMessage.classList.add('hidden');
                    progressBar.style.width = '0%';
                }, 5000);
            }, 500);
        }

        /**
         * ✅ MOBILE-OPTIMIZED: Notification system - APPEARS AT BOTTOM ON MOBILE
         */
        function showNotification(message, type = 'info') {
            const existingNotifications = document.querySelectorAll('.download-notification');
            existingNotifications.forEach(notif => notif.remove());
            
            const notification = document.createElement('div');
            notification.className = 'download-notification';
            
            notification.style.cssText = `
                position: fixed;
                bottom: 80px;
                left: 50%;
                transform: translateX(-50%) translateY(120px) scale(0.9);
                max-width: calc(100vw - 30px);
                width: calc(100vw - 30px);
                padding: 24px;
                border-radius: 16px;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
                border: 4px solid;
                opacity: 0;
                transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                z-index: 999999 !important;
                pointer-events: auto !important;
            `;
            
            const bgColors = {
                'success': 'background: linear-gradient(135deg, #23A455 0%, #1a7f3f 100%);',
                'error': 'background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);',
                'info': 'background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);'
            };
            
            const borderColors = {
                'success': 'border-color: #34d399;',
                'error': 'border-color: #f87171;',
                'info': 'border-color: #60a5fa;'
            };
            
            notification.style.cssText += bgColors[type] || bgColors.info;
            notification.style.cssText += borderColors[type] || borderColors.info;
            
            const icons = {
                'success': '📥',
                'error': '❌',
                'info': 'ℹ️'
            };
            
            notification.innerHTML = `
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white bg-opacity-25 rounded-full flex items-center justify-center" style="backdrop-filter: blur(10px);">
                            <span class="text-2xl font-bold text-white">${icons[type] || icons.info}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-base font-bold leading-tight text-white" style="text-shadow: 0 2px 4px rgba(0,0,0,0.2);">${message}</p>
                        <p class="text-xs text-white text-opacity-80 mt-1" style="text-shadow: 0 1px 2px rgba(0,0,0,0.2);">Tap to dismiss</p>
                    </div>
                    <div class="flex-shrink-0">
                        <button onclick="this.closest('.download-notification').remove()" class="text-white hover:text-gray-200 text-2xl font-bold leading-none opacity-75 hover:opacity-100 transition-opacity">&times;</button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in - SLIDE UP FROM BOTTOM
            setTimeout(() => {
                notification.style.transform = 'translateX(-50%) translateY(0) scale(1)';
                notification.style.opacity = '1';
            }, 50);
            
            // Auto remove after 7 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.style.transform = 'translateX(-50%) translateY(120px) scale(0.8)';
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 500);
                }
            }, 7000);
        }

        function buyCreditPaystack(){
            let amount = '<?php echo $resource->price ; ?>'
            let baseamount = Number(amount * 100)
            let currency = '<?php echo $resource->currency ; ?>'
            let email = '<?php echo auth()->user()->email ; ?>'
            let firstname = '<?php echo auth()->user()->first_name ; ?>'
            let lastname = '<?php echo auth()->user()->last_name ; ?>'
        
            let meta = {}
            meta.user_id = '<?php echo auth()->user()->id ; ?>'
            meta.description = 'download resource on readprojecttopics'
            meta.txntype = 'downloadresource'

            payWithPaystack(email, baseamount, firstname, lastname, currency, meta);
        }

        function generateREF(){
            var d = new Date().getTime();
            if(window.performance && typeof window.performance.now === "function"){
                d += performance.now();
            }
            var ref = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                var r = (d + Math.random()*16)%16 | 0;
                d = Math.floor(d/16);
                return (c=='x' ? r : (r&0x3|0x8)).toString(16);
            });
            return ref;
        }

        function payWithPaystack(email, baseamount, firstname, lastname, currency, meta){
            var handler = PaystackPop.setup({
                key: public_key,
                email: email,
                firstname: firstname,
                lastname: lastname,
                amount: baseamount,
                currency: currency,
                ref: generateREF(), 
                metadata: meta,
                callback: function(response){
                    showNotification('💳 Payment successful! Starting download...', 'success');
                    let query = encodeURIComponent(JSON.stringify(response))
                    window.location = '<?php echo route('resources.download', $resource->slug) ; ?>' + '?response=' + query
                },
                onClose: function(){
                    var msg = 'Payment cancelled. Click "Pay with Credit" to try again.';
                    showNotification(msg, 'error');
                }
            });
            handler.openIframe();
        }
    </script>
@endpush