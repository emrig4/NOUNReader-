<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="google-site-verification" content="kxif4mNzcVplMsLcmLHjvyQV5XVbC6UPmpV3rYgMShk" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@stack('meta')
		<title>readprojecttopics - {{ (isset($title)) ? $title : '' }}</title>
<!-- Favicon for all browsers -->
<link rel="icon" href="{{ theme_asset('admin/assets/images/favicon/favicon.ico') }}" sizes="32x32">
<link rel="icon" href="{{ theme_asset('admin/assets/images/favicon/favicon.ico') }}" sizes="128x128">
<link rel="icon" href="{{ theme_asset('admin/assets/images/favicon/favicon.ico') }}" sizes="192x192">
<link rel="shortcut icon" href="{{ theme_asset('admin/assets/images/favicon/favicon.ico') }}" sizes="196x196">
<link rel="apple-touch-icon" href="{{ theme_asset('admin/assets/images/favicon/favicon.ico') }}" sizes="152x152">
<link rel="apple-touch-icon" href="{{ theme_asset('admin/assets/images/favicon/favicon.ico') }}" sizes="167x167">
<link rel="apple-touch-icon" href="{{ theme_asset('admin/assets/images/favicon/favicon.ico') }}" sizes="180x180">
        @notifyCss

		<!-- Css Files -->
		<link href="{{ theme_asset('css/bootstrap.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/font-awesome.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/flaticon.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/slick-slider.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/fancybox.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/style.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/color.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/responsive.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/typography.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/scrollbar.css') }}" rel="stylesheet">
		
		<!-- ✅ MISSING CSS FILE - ADD THIS LINE -->
		<link href="{{ theme_asset('css/search-mega.css') }}" rel="stylesheet">

		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

		 <!-- theme -->
		<link href="{{ theme_asset('css/theme.css') }}" rel="stylesheet">

		<!-- tailwinfd css -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.0.2/tailwind.min.css" rel="stylesheet">
		<!-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> -->



		<!-- Styles -->
	    @livewireStyles
		@stack('css')

	</head>
	<body class="ereaders-sticky">

		<!--// Main Wrapper \\-->
		<div class="ereaders-main-wrapper">

			<!-- include header here -->
			@include('partials.header')

			@yield('banner')

			<!--// Main Content \\-->
			<div class="ereaders-main-content ereaders-content-padding">
				@yield('content')
			</div>
			<!--// Main Content \\-->


			<!-- Footer start -->
			@include('partials.footer')
			<!-- Footer end -->

			<div class="clearfix"></div>
		</div>
		<!--// Main Wrapper \\-->

		<!-- jQuery (necessary for JavaScript plugins) -->
		<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

		<!-- jquery ui -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js" integrity="sha512-uto9mlQzrs59VwILcLiRYeLKPPbS/bT71da/OEBYEwcdNUk8jYIy+D176RYoop1Da+f9mvkYrmj5MCLZWEtQuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

		<script type="text/javascript" src="{{ theme_asset('js/bootstrap.min.js') }}"></script>
		<script type="text/javascript" src="{{ theme_asset('js/slick.slider.min.js') }}"></script>
		<script type="text/javascript" src="{{ theme_asset('js/fancybox.pack.js') }}"></script>
		<script type="text/javascript" src="{{ theme_asset('js/isotope.min.js') }}"></script>
		<script type="text/javascript" src="{{ theme_asset('js/progressbar.js') }}"></script>
		<script type="text/javascript" src="{{ theme_asset('js/jquery.countdown.min.js') }}"></script>
		<script type="text/javascript" src="{{ theme_asset('js/circle-chart.js') }}"></script>
		<script type="text/javascript" src="{{ theme_asset('js/numscroller.js') }}"></script>
		<script type="text/javascript" src="{{ theme_asset('js/functions.js') }}"></script>
		<script src="{{ mix('js/app.js') }}" defer></script>

		@stack('js')
		@livewireScripts
{{-- <x-notify-messages /> --}}        @notifyJs

        <!--Start of Tawk.to Script-->
        @php
            echo setting('tawk_widget');
        @endphp
        <!--End of Tawk.to Script-->
        
       <!-- readprojecttopics WhatsApp Business Chat Widget - Improved Version -->
<div id="readprojecttopics-whatsapp-widget" class="readprojecttopics-whatsapp-widget">
    <!-- WhatsApp Button -->
    <div class="whatsapp-button" id="whatsapp-btn">
        <div class="whatsapp-icon">
            <svg viewBox="0 0 24 24" fill="currentColor">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
        </div>
        <div class="whatsapp-pulse"></div>
        <div class="whatsapp-notification" id="whatsapp-notification" style="display: none;">3</div>
    </div>

    <!-- Chat Popup -->
    <div class="whatsapp-popup" id="whatsapp-popup">
        <div class="whatsapp-header">
            <div class="whatsapp-agent-info">
                <div class="whatsapp-avatar">
                    <img src="{{ asset('themes/airdgereaders/images/female.png') }}" alt="readprojecttopics Support" />
                    <div class="online-status"></div>
                </div>
                <div class="whatsapp-info">
                    <h4>PAM Support</h4>
                    <span class="whatsapp-status">Online • Usually replies instantly</span>
                </div>
            </div>
            <button class="whatsapp-close" onclick="closeWhatsAppPopup()">&times;</button>
        </div>

        <div class="whatsapp-body">
            <div class="whatsapp-welcome">
                <div class="welcome-avatar">
                    <img src="{{ asset('themes/airdgereaders/images/female.png') }}" alt="Readprojecttopics" />
                </div>
                <div class="welcome-content">
                   <!--  <h3>Welcome! 🎓</h3>
                    <p>Hi there! 👋 We're here to help you with:</p>
                    <ul>
                        <li>📚 Buying unit Credits to read and download project materials</li>
                        <li>✍️ Hiring professional writers to asist in your new projrct writing</li>
                        <li>💬 General support & guidance</li> --> 
                    </ul>
                    <p>Choose an option below to get started:</p>
                </div>
            </div>

            <div class="whatsapp-options">
                <button class="whatsapp-option" onclick="sendWhatsAppMessage('Hi! I want to buy Ranc Credits for my account.')">
                    <span class="option-icon">💳</span>
                    <div class="option-content">
                        <strong>Buy unit Credits</strong>
                        <small>Purchase credits to read and download projects</small>
                    </div>
                </button>

                <button class="whatsapp-option" onclick="sendWhatsAppMessage('Hi! I need to hire a professional writer for my project.')">
                    <span class="option-icon">✍️</span>
                    <div class="option-content">
                        <strong>Hire a Writer</strong>
                        <small>Get expert writing assistance</small>
                    </div>
                </button>

                <button class="whatsapp-option" onclick="sendWhatsAppMessage('Hi! I need help with my account and general support.')">
                    <span class="option-icon">💬</span>
                    <div class="option-content">
                        <strong>General Support</strong>
                        <small>Account help & technical support</small>
                    </div>
                </button>
            </div>

            <div class="whatsapp-footer">
                <p><small>🔒 Your conversations are secure with WhatsApp</small></p>
            </div>
        </div>
    </div>
</div>

<style>
/* readprojecttopics WhatsApp Widget Styles - Improved Version */
.readprojecttopics-whatsapp-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 10000;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
}

/* WhatsApp Button */
.whatsapp-button {
    width: 65px;
    height: 65px;
    background: linear-gradient(135deg, #25D366, #128C7E);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
    transition: all 0.3s ease;
    position: relative;
    /* Removed floating animation to reduce visual disruption */
}

/* Keep subtle hover effect */
.whatsapp-button:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 25px rgba(37, 211, 102, 0.6);
}

.whatsapp-icon {
    width: 32px;
    height: 32px;
    color: white;
    z-index: 2;
}

/* Reduced pulse animation - less disruptive */
.whatsapp-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: rgba(37, 211, 102, 0.2);
    /* Slower, less frequent pulse */
    animation: subtlePulse 4s infinite;
}

/* Subtle pulse animation - much less disruptive */
@keyframes subtlePulse {
    0% {
        transform: scale(1);
        opacity: 0.3;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.1;
    }
    100% {
        transform: scale(1);
        opacity: 0.3;
    }
}

/* Notification Badge - Hidden by default */
.whatsapp-notification {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4444;
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: none; /* Hidden by default */
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    border: 2px solid white;
    /* Removed bounce animation - too disruptive */
}

/* Chat Popup */
.whatsapp-popup {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 380px;
    height: 520px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: popupSlide 0.4s ease;
}

.whatsapp-popup.active {
    display: flex;
}

@keyframes popupSlide {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Header */
.whatsapp-header {
    background: linear-gradient(135deg, #25D366, #128C7E);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.whatsapp-agent-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.whatsapp-avatar {
    position: relative;
    width: 45px;
    height: 45px;
}

.whatsapp-avatar img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: 2px solid white;
}

.online-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    background: #4CAF50;
    border: 2px solid white;
    border-radius: 50%;
}

.whatsapp-info h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.whatsapp-status {
    font-size: 13px;
    opacity: 0.9;
}

.whatsapp-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

.whatsapp-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Body */
.whatsapp-body {
    flex: 1;
    padding: 0;
    overflow-y: auto;
    background: #f0f2f5;
}

.whatsapp-welcome {
    padding: 25px;
    background: white;
    border-bottom: 1px solid #e0e0e0;
}

.welcome-avatar {
    text-align: center;
    margin-bottom: 15px;
}

.welcome-avatar img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
}

.welcome-content h3 {
    margin: 0 0 10px 0;
    color: #128C7E;
    font-size: 18px;
}

.welcome-content p {
    margin: 0 0 8px 0;
    color: #666;
    font-size: 14px;
    line-height: 1.4;
}

.welcome-content ul {
    margin: 10px 0;
    padding-left: 20px;
}

.welcome-content li {
    color: #666;
    font-size: 13px;
    margin-bottom: 5px;
}

/* Options */
.whatsapp-options {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.whatsapp-option {
    background: white;
    border: none;
    padding: 15px;
    border-radius: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    text-align: left;
    width: 100%;
}

.whatsapp-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
    background: #f8fffe;
}

.option-icon {
    font-size: 24px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f2f5;
    border-radius: 10px;
}

.option-content {
    flex: 1;
}

.option-content strong {
    display: block;
    color: #333;
    font-size: 15px;
    margin-bottom: 2px;
}

.option-content small {
    color: #666;
    font-size: 13px;
}

/* Footer */
.whatsapp-footer {
    padding: 15px 20px;
    text-align: center;
    background: white;
    border-top: 1px solid #e0e0e0;
}

.whatsapp-footer p {
    margin: 0;
    color: #999;
    font-size: 12px;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .readprojecttopics-whatsapp-widget {
        bottom: 15px;
        right: 15px;
    }
    
    .whatsapp-button {
        width: 60px;
        height: 60px;
    }
    
    .whatsapp-popup {
        position: fixed;
        bottom: 0;
        right: 0;
        left: 0;
        top: auto;
        width: 100%;
        height: 70vh;
        border-radius: 20px 20px 0 0;
    }
    
    .whatsapp-body {
        height: calc(70vh - 80px);
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .whatsapp-popup {
        background: #2a2a2a;
        color: white;
    }
    
    .whatsapp-body {
        background: #1a1a1a;
    }
    
    .whatsapp-welcome,
    .whatsapp-option {
        background: #2a2a2a;
        color: white;
    }
    
    .whatsapp-footer {
        background: #2a2a2a;
        border-color: #444;
    }
    
    .option-content strong {
        color: white;
    }
    
    .option-content small {
        color: #ccc;
    }
    
    .welcome-content p,
    .welcome-content li {
        color: #ccc;
    }
}

/* User preference storage styles */
.whatsapp-user-preference {
    position: absolute;
    bottom: 85px;
    right: 0;
    background: white;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
    display: none;
    z-index: 10001;
    min-width: 280px;
}

.whatsapp-user-preference.active {
    display: block;
}

.preference-title {
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
}

.preference-options {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.preference-option {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    background: #f9f9f9;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 14px;
}

.preference-option:hover {
    background: #25D366;
    color: white;
    border-color: #25D366;
}

.preference-option.active {
    background: #25D366;
    color: white;
    border-color: #25D366;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const whatsappBtn = document.getElementById('whatsapp-btn');
    const whatsappPopup = document.getElementById('whatsapp-popup');
    const notification = document.getElementById('whatsapp-notification');
    let isPopupOpen = false;
    let hasUserInteracted = false;

    // Get user preference from localStorage
    const userPreference = localStorage.getItem('whatsappPopupPreference') || 'auto';

    // Initialize based on user preference
    initializeWhatsAppWidget(userPreference);

    function initializeWhatsAppWidget(preference) {
        switch(preference) {
            case 'auto':
                // Auto-show after extended delay (30 seconds instead of 10)
                setTimeout(function() {
                    if (!hasUserInteracted && !isPopupOpen) {
                        showNotificationBadge();
                    }
                }, 30000); // Extended to 30 seconds
                break;
            case 'manual':
                // Only show button, no auto-popup
                console.log('WhatsApp widget set to manual mode');
                break;
            case 'delayed':
                // Auto-show after very long delay (2 minutes)
                setTimeout(function() {
                    if (!hasUserInteracted && !isPopupOpen) {
                        showNotificationBadge();
                    }
                }, 120000); // 2 minutes
                break;
            default:
                // Default to manual if preference not recognized
                console.log('WhatsApp widget using default manual mode');
        }
    }

    // Toggle popup
    whatsappBtn.addEventListener('click', function() {
        hasUserInteracted = true;
        isPopupOpen = !isPopupOpen;
        whatsappPopup.classList.toggle('active', isPopupOpen);
        
        // Hide notification when opened
        if (isPopupOpen) {
            hideNotificationBadge();
        }
    });

    // Close popup when clicking outside
    document.addEventListener('click', function(event) {
        if (!whatsappBtn.contains(event.target) && 
            !whatsappPopup.contains(event.target) && 
            isPopupOpen) {
            closeWhatsAppPopup();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && isPopupOpen) {
            closeWhatsAppPopup();
        }
    });

    // Show notification badge (only for auto mode)
    function showNotificationBadge() {
        if (notification && localStorage.getItem('whatsappPopupPreference') !== 'manual') {
            notification.style.display = 'flex';
            notification.style.animation = 'subtleNotification 0.5s ease-in-out';
        }
    }

    // Hide notification badge
    function hideNotificationBadge() {
        if (notification) {
            notification.style.display = 'none';
        }
    }

    // Add subtle notification animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes subtleNotification {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    `;
    document.head.appendChild(style);

    // Context menu for power users (right-click on button)
    whatsappBtn.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        showPreferenceMenu(e);
    });

    // Preference menu
    function showPreferenceMenu(event) {
        // Remove existing preference menu if any
        const existingMenu = document.querySelector('.whatsapp-user-preference');
        if (existingMenu) {
            existingMenu.remove();
        }

        const preferenceMenu = document.createElement('div');
        preferenceMenu.className = 'whatsapp-user-preference';
        
        const currentPref = localStorage.getItem('whatsappPopupPreference') || 'auto';
        
        preferenceMenu.innerHTML = `
            <div class="preference-title">WhatsApp Widget Settings</div>
            <div class="preference-options">
                <div class="preference-option ${currentPref === 'auto' ? 'active' : ''}" onclick="setWhatsAppPreference('auto')">
                    Auto-popup (30s delay)
                </div>
                <div class="preference-option ${currentPref === 'delayed' ? 'active' : ''}" onclick="setWhatsAppPreference('delayed')">
                    Auto-popup (2min delay)
                </div>
                <div class="preference-option ${currentPref === 'manual' ? 'active' : ''}" onclick="setWhatsAppPreference('manual')">
                    Manual only (click to open)
                </div>
            </div>
        `;

        document.body.appendChild(preferenceMenu);
        
        // Position menu near button
        const rect = whatsappBtn.getBoundingClientRect();
        preferenceMenu.style.bottom = '80px';
        preferenceMenu.style.right = '0';
        
        // Show menu
        setTimeout(() => preferenceMenu.classList.add('active'), 10);
        
        // Hide menu when clicking elsewhere
        setTimeout(() => {
            document.addEventListener('click', function hideMenu(e) {
                if (!preferenceMenu.contains(e.target) && e.target !== whatsappBtn) {
                    preferenceMenu.classList.remove('active');
                    setTimeout(() => preferenceMenu.remove(), 300);
                    document.removeEventListener('click', hideMenu);
                }
            });
        }, 100);
    }

    // Track user engagement
    let engagementTimer = 0;
    const engagementThreshold = 30; // 30 seconds of engagement

    // Start tracking user engagement
    const engagementStart = Date.now();
    
    document.addEventListener('scroll', function() {
        engagementTimer += 1;
    });
    
    document.addEventListener('click', function() {
        engagementTimer += 5; // Clicks count more heavily
    });

    // Show notification for engaged users
    setTimeout(function() {
        const currentTime = Date.now();
        const timeSpent = (currentTime - engagementStart) / 1000; // in seconds
        
        // Only show if user has been engaged for at least threshold time
        if (timeSpent >= engagementThreshold && engagementTimer >= 10) {
            showNotificationBadge();
        }
    }, 45000); // Check after 45 seconds
});

// Global function to set user preference
function setWhatsAppPreference(preference) {
    localStorage.setItem('whatsappPopupPreference', preference);
    
    // Hide preference menu
    const menu = document.querySelector('.whatsapp-user-preference');
    if (menu) {
        menu.classList.remove('active');
        setTimeout(() => menu.remove(), 300);
    }
    
    // Show confirmation
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #25D366;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 14px;
        z-index: 10002;
        animation: slideInRight 0.3s ease;
    `;
    notification.textContent = 'WhatsApp widget preference saved!';
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 2000);
    
    // Add animation styles
    if (!document.querySelector('#preference-animation-styles')) {
        const animStyles = document.createElement('style');
        animStyles.id = 'preference-animation-styles';
        animStyles.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(animStyles);
    }
}

// Close popup function
function closeWhatsAppPopup() {
    document.getElementById('whatsapp-popup').classList.remove('active');
    isPopupOpen = false;
}

// Send WhatsApp message function
function sendWhatsAppMessage(message) {
    // IMPORTANT: Replace with your WhatsApp Business phone number
    // Format: Country code + number without + or spaces
    // Example: +1234567890 becomes 1234567890
    const phoneNumber = '2349038349959'; // Replace with your actual number
    
    const encodedMessage = encodeURIComponent(message);
    const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;
    
    // Open WhatsApp in new window/tab
    window.open(whatsappURL, '_blank');
    
    // Close popup
    closeWhatsAppPopup();
    
    // Track analytics (if you have Google Analytics)
    if (typeof gtag !== 'undefined') {
        gtag('event', 'whatsapp_click', {
            'event_category': 'engagement',
            'event_label': message,
            'value': 1
        });
    }
    
    // Track with Facebook Pixel (if installed)
    if (typeof fbq !== 'undefined') {
        fbq('track', 'Lead', {
            content_name: 'WhatsApp Chat',
            content_category: 'Support'
        });
    }
}
</script>
		<!-- JavaScript Console Error Fixes -->
<script src="{{ theme_asset('js/fixes/vue-fix.js') }}"></script>
<script src="{{ theme_asset('js/fixes/font-fix.js') }}"></script>
<script src="{{ theme_asset('js/fixes/pdf-worker-fix.js') }}"></script>

<!-- Vue PDF Component -->
<script src="{{ asset('js/vue-pdf-embed-s3.js') }}"></script>
	</body>

</html>

