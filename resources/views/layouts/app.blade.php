<!DOCTYPE html>
<html lang="{{App::getLocale()}}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#1f1f1f">
	<meta name="msapplication-navbutton-color" content="#1f1f1f">
	<meta name="apple-mobile-web-app-status-bar-style" content="#1f1f1f">
	<meta name="csrf-token" content="{{csrf_token()}}">
	@yield('meta')
	<link rel="icon" href="{{asset('icons/favicon.ico')}}">
	<meta property="og:image" content="{{asset('icons/og-favicon.png')}}" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/all.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/slick.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/custom-swal.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancyx/fancybox.css" />
	@yield('style')
    <style>
        .page-assist {
            position: fixed;
            right: 30px;
            max-width: 450px;
            bottom: 30px;
            background-color: #28282826 !important;
            backdrop-filter: blur(9px);
            border-radius: 8px !important;
            border: 1px solid #505050 !important;
            color: white !important;
            padding: 20px 30px;
            z-index: 1;
        }
        .page-assist svg path{
            transition: all 0.3s linear;
        }
        .pa-header {
            padding-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }
        .pa-header b{
            color: #ff8d11;
            font-size: 110%;
        }
        .pa-header span{
            padding-top: 5px;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            justify-items: center;
        }
        .pa-header span svg path{
            fill: white
        }
        .pa-header span:hover svg path{
            fill: #ff8d11
        }
        .pa-content {
            padding-bottom: 20px;
            white-space: pre-line;
        }
        .pa-content a{
            color: #ff8d11;
            text-decoration: underline;
        }
        .pa-content a:hover{
            text-decoration: none;
        }
    </style>
</head>
<body>
	<div id="wrapper">
        <div class="hidden" data-flashnotif="{{json_encode(getActiveFlash())}}"></div>
		@yield('page-content')
		<footer class="footer">
			<div class="holder">
				<div class="footer-block">
					<div class="footer-copy">
						&copy; {{date("Y")}}
						<span>«Rigmanager»</span> - @lang('ui.introduction'). @lang('ui.footerCopyright')
					</div>
					<div class="footer-col">
						<ul class="footer-nav">
							<li><a href="{{route('about')}}">@lang('ui.footerAbout')</a></li>
							<li><a href="{{route('blog.index')}}">@lang('ui.footerBlog')</a></li>
							<li><a href="{{route('search')}}">@lang('ui.catalog')</a></li>
						</ul>
					</div>
					<div class="footer-col">
						<ul class="footer-nav">
							<li><a href="{{route('plans.index')}}">@lang('ui.footerSubscription')</a></li>
							<li><a href="{{route('feedbacks.create')}}">@lang('ui.footerContact')</a></li>
							<li><a href="{{route('faq')}}">FAQ</a></li>
						</ul>
					</div>
					<div class="footer-col">
						<ul class="footer-nav">
							<li><a href="{{route('terms')}}">@lang('ui.footerTerms')</a></li>
							<li><a href="{{route('privacy')}}">@lang('ui.footerPrivacy')</a></li>
							<li><a href="{{route('site-map')}}">@lang('ui.footerSiteMap')</a></li>
						</ul>
					</div>
					<div class="footer-col">
						<ul class="footer-nav">
							<li><a href="https://www.linkedin.com/company/rigmanagers-com/about">LinedIn</a></li>
							<li><a href="https://www.facebook.com/rigmanagerscom">Facebook</a></li>
						</ul>
					</div>
				</div>
			</div>
		</footer>
		<div class="development-alert d-none">
			<p>
                @lang('ui.development')
                <a href="{{route('feedbacks.create')}}">@lang('ui.footerContact')</a>
            </p>
		</div>
	</div>

    <div id="page-assist-importCreate" class="page-assist d-none">
        <div>
            <div class="pa-header">
                <b>@lang('ui.page-assists.importCreate.title')</b>
                <span class="pa-close">
                    @svg('icons/close.svg')
                </span>
            </div>
            <div class="pa-content">@lang('ui.page-assists.importCreate.body')</div>
        </div>
    </div>

    <div id="page-assist-importValidationErrors" class="page-assist d-none">
        <div>
            <div class="pa-header">
                <b>@lang('ui.page-assists.importValidationErrors.title')</b>
                <span class="pa-close">
                    @svg('icons/close.svg')
                </span>
            </div>
            <div class="pa-content">@lang('ui.page-assists.importValidationErrors.body')</div>
        </div>
    </div>

    <div id="page-assist-postCreate" class="page-assist d-none">
        <div>
            <div class="pa-header">
                <b>@lang('ui.page-assists.postCreate.title')</b>
                <span class="pa-close">
                    @svg('icons/close.svg')
                </span>
            </div>
            <div class="pa-content">@lang('ui.page-assists.postCreate.body')</div>
        </div>
    </div>

	@yield('modals')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="{{asset('js/jquery-2.2.4.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate-additional.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
	<script src="{{asset('js/slick.min.js')}}"></script>
	<script src="{{asset('js/jquery-ui-2.min.js')}}"></script>
	<script src="{{asset('js/typeahead.bundle.js')}}"></script>
	<script src="{{asset('js/all.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{config('services.recaptcha.public_key')}}"></script>
    @vite('resources/js/app.js')

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9RPT79VDXE"></script>
    @if (app('env') != 'local')
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-9RPT79VDXE');
        </script>
    @endif

	<script type="text/javascript">
        window.Laravel = {!!$LaravelDataForJS!!};
        console.log(`window.Laravel`, window.Laravel); //! LOG
    </script>
    @yield('scripts')

    <noscript>
        <div id="noscript">
            <p>@lang('ui.noscript')</p>
        </div>
    </noscript>
</body>
</html>
