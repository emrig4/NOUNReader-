<!DOCTYPE html>
<html lang="en">
	<head>
		<meta name="google-site-verification" content="kxif4mNzcVplMsLcmLHjvyQV5XVbC6UPmpV3rYgMShk" />
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		@seoTags()
		@stack('meta')
		<title>readprojecttopics - {{ (isset($title)) ? $title : '' }}</title>

		<!-- tailwinfd css -->
		<!-- <script src="https://unpkg.com/tailwindcss-jit-cdn"></script> -->
		<link rel="stylesheet" href="{{ mix('css/app.css') }}">


		<meta property="og:url"           content="https://www.your-domain.com/your-page.html" />
	    <meta property="og:type"          content="website" />
	    <meta property="og:title"         content="Your Website Title" />
	    <meta property="og:description"   content="Your description" />
	    <meta property="og:image"         content="https://www.your-domain.com/path/image.jpg" />

        @notifyCss

		<!-- Css Files -->
		<link href="{{ theme_asset('css/bootstrap.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/font-awesome.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/style.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/color.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/responsive.css') }}" rel="stylesheet">
		<link href="{{ theme_asset('css/typography.css') }}" rel="stylesheet">

		 <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		 <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		 <!--[if lt IE 9]>
			 <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			 <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		 <![endif]-->

		@stack('css')
	</head>
	<body class="ereaders-sticky">
		<!--// Main Wrapper \\-->
		<div class="ereaders-main-wrapper">

		@yield('banner')

		<!--// Main Content \\-->
		<div class="ereaders-main-content ereaders-content-padding">
			@yield('content')
		</div>
		<!--// Main Content End \\-->

		<div class="clearfix"></div>

		</div>
		<!--// Main Wrapper end \\-->

		 <!-- jQuery (necessary for JavaScript plugins) -->
		 <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		 <script type="text/javascript" src="{{ theme_asset('js/bootstrap.min.js') }}"></script>

		@stack('js')
		
		<!-- JavaScript Console Error Fixes -->
<script src="{{ theme_asset('js/fixes/vue-fix.js') }}"></script>
<script src="{{ theme_asset('js/fixes/font-fix.js') }}"></script>
<script src="{{ theme_asset('js/fixes/pdf-worker-fix.js') }}"></script>

<!-- Vue PDF Component -->
<script src="{{ asset('js/vue-pdf-embed-s3.js') }}"></script>

		 <!-- livewireScripts -->
        <x:notify-messages />
        @notifyJs

        <!--Start of Tawk.to Script-->
        @php
            echo setting('tawk_widget');
        @endphp
        <!--End of Tawk.to Script-->
	</body>
</html>