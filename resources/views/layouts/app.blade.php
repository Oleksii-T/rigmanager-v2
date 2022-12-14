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
	{{-- <link rel="canonical" href="{{url()->full()}}">
	<link rel="alternate" href="{{hreflang_url(url()->full(), 'uk')}}" hreflang="x-default">
	<link rel="alternate" href="{{hreflang_url(url()->full(), 'uk')}}" hreflang="uk">
	<link rel="alternate" href="{{hreflang_url(url()->full(), 'ru')}}" hreflang="ru">
	<link rel="alternate" href="{{hreflang_url(url()->full(), 'en')}}" hreflang="en"> --}}
	<link rel="icon" href="{{asset('icons/favicon.ico')}}">
	<meta property="og:image" content="{{asset('icons/og-favicon.png')}}" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/all.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/slick.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}" />
</head>
<body>
	<div id="wrapper">
        <div class="hidden" data-flash="{{json_encode(getActiveFlash())}}" flash-notif-data></div>
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
							<li><a href="#">@lang('ui.footerBlog')</a></li>
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
						<ul class="footer-nav footer-langs">
                            @foreach(LaravelLocalization::getLocalesOrder() as $localeCode => $properties)
                                @if ($currentLocale != $localeCode)
								    <li class="active">
                                        <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                            {{ $localeCode }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
						</ul>
					</div>
				</div>
			</div>
		</footer>
		<div class="development-alert hidden">
			<p>@lang('ui.development')</p>
		</div>
	</div>

	@yield('modals')

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="{{asset('js/jquery-2.2.4.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate-additional.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
	<script src="{{asset('js/jquery.fancybox.min.js')}}"></script>
	<script src="{{asset('js/slick.min.js')}}"></script>
	<script src="{{asset('js/jquery-ui-2.min.js')}}"></script>
	<script src="{{asset('js/all.js')}}"></script>
	<script type="text/javascript">
        $(document).ready(function(){
			//block not-reday links
			$('.not-ready').click(function(e){
				e.preventDefault();
				showToast(false, "{{ __('messages.inProgress') }}");
			});
        });
    </script>
    @yield('scripts')

    <noscript>
        <div id="noscript">
            <p>@lang('ui.noscript')</p>
        </div>
    </noscript>
</body>
</html>
