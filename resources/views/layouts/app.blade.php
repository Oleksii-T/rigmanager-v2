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
    <link rel="canonical" href="@yield('canonical', 'https://rigmanagers.com'.request()->getRequestUri())" />
    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        @php
            $hreflang = $localeCode == config('app.default_locale') ? 'x-default' : $localeCode;
        @endphp
        <link rel="alternate" href="{{LaravelLocalization::getLocalizedURL($localeCode)}}" hreflang="{{$hreflang}}">
    @endforeach
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/all.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/slick.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/custom-swal.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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
        .pa-close{
            padding-top: 5px;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            justify-items: center;
        }
        .pa-close svg path{
            fill: white
        }
        .pa-close:hover svg path{
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
        .report-category-fields-suggestion {
            color: #c21616;
            cursor: pointer;
        }
        .cfields {
            padding-left: 10px;
            display: block;
            border-left: 2px solid #505050;
            cursor: pointer;
            transition: all .3s linear;
        }
        .cfields:hover {
            border-left: 2px solid #ff8d11;
        }
    </style>
</head>
<body>
	<div id="wrapper">
        <div class="hidden" data-flashnotif="{{json_encode(getActiveFlash())}}"></div>

		@yield('page-content')
		
        <x-footer />

		<div class="development-alert d-none">
			<p>
                @lang('ui.development')
                <a href="{{route('feedbacks.create')}}">@lang('ui.footerContact')</a>
            </p>
		</div>
	</div>

    <x-page-assists />

	@yield('modals')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="{{asset('js/jquery-2.2.4.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate-additional.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
	<script src="{{asset('js/slick.min.js')}}"></script>
	<script src="{{asset('js/jquery-ui-2.min.js')}}"></script>
	<script src="{{asset('js/typeahead.bundle.js')}}"></script>
    @vite('resources/js/app.js')

    <script type="text/javascript">
        window.Laravel = {!!$LaravelDataForJS!!};
        console.log(`window.Laravel`, window.Laravel); //! LOG
    </script>

	<script src="{{asset('js/all.js')}}"></script>

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

    @yield('scripts')

    <noscript>
        <div id="noscript">
            <p>@lang('ui.noscript')</p>
        </div>
    </noscript>
</body>
</html>
