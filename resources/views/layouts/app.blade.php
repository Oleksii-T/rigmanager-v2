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
	@yield('meta')
	{{-- <link rel="canonical" href="{{url()->full()}}">
	<link rel="alternate" href="{{hreflang_url(url()->full(), 'uk')}}" hreflang="x-default">
	<link rel="alternate" href="{{hreflang_url(url()->full(), 'uk')}}" hreflang="uk">
	<link rel="alternate" href="{{hreflang_url(url()->full(), 'ru')}}" hreflang="ru">
	<link rel="alternate" href="{{hreflang_url(url()->full(), 'en')}}" hreflang="en"> --}}
	<link rel="icon" href="{{asset('icons/favicon.ico')}}">
	<meta property="og:image" content="{{asset('icons/og-favicon.png')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/all.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/custom.css')}}" />
</head>
<body>
	<div id="wrapper">
		<div id="pop-up-container">
			<!-- Session flash massages -->
			@if (Session::has('message-success'))
				<div class="flash flash-success">
					<p><img src="{{asset('icons/success.svg')}}" alt="{{__('alt.keyword')}}">{{ Session::get('message-success') }}</p>
					<div class="animated-line"></div>
				</div>
			@endif
			@if (Session::has('message-error'))
				<div class="flash flash-error">
					<p><img src="{{asset('icons/warning.svg')}}" alt="{{__('alt.keyword')}}">{{ Session::get('message-error') }}</p>
					<div class="animated-line"></div>
				</div>
			@endif
			@if (session('status'))
				<div class="flash flash-success">
					<p><img src="{{asset('icons/success.svg')}}" alt="{{__('alt.keyword')}}">{{ session('status') }}</p>
					<div class="animated-line"></div>
				</div>
			@endif
		</div>
		@yield('page-content')
		<footer class="footer">
			<div class="holder">
				<div class="footer-block">
					<div class="footer-copy">
						&copy; {{ date("Y") }}
						<span>«Rigmanager»</span> - {{__('ui.introduction')}}. {{__('ui.footerCopyright')}}
					</div>
					<div class="footer-col">
						<ul class="footer-nav">
							<li><a href="#">{{__('ui.footerAbout')}}</a></li>
							<li><a href="#">{{__('ui.footerBlog')}}</a></li>
							<li><a href="#">{{__('ui.catalog')}}</a></li>
						</ul>
					</div>
					<div class="footer-col">
						<ul class="footer-nav">
							<li><a href="#">{{__('ui.footerSubscription')}}</a></li>
							<li><a href="#">{{__('ui.footerContact')}}</a></li>
							<li><a href="#">FAQ</a></li>
						</ul>
					</div>
					<div class="footer-col">
						<ul class="footer-nav">
							<li><a href="#">{{__('ui.footerTerms')}}</a></li>
							<li><a href="#">{{__('ui.footerPrivacy')}}</a></li>
							<li><a href="#">{{__('ui.footerSiteMap')}}</a></li>
						</ul>
					</div>
					<div class="footer-col">
						<ul class="footer-nav footer-langs">
							@if (App::isLocale('uk'))
								<li class="active">Ukr</li>
							@else
								<li><a href="#">Ukr</a></li>
							@endif
							@if (App::isLocale('en'))
								<li class="active">Eng</li>
							@else
								<li><a href="#">Eng</a></li>
							@endif
							@if (App::isLocale('ru'))
								<li class="active">Rus</li>
							@else
								<li><a href="#">Rus</a></li>
							@endif
						</ul>
					</div>
				</div>
			</div>
		</footer>
		<div class="development-alert hidden">
			<p>{{__('ui.development')}}</p>
		</div>
	</div>
	@yield('modals')
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/jquery.fancybox.min.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/slick.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/jquery-ui.css')}}" />
	<link media="all" rel="stylesheet" type="text/css" href="{{asset('css/dropzone.css')}}" />
	<script src="{{asset('js/jquery-2.2.4.min.js')}}"></script>
	<script type="text/javascript" src="{{ asset('js/jquery.validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.validate-additional.js') }}"></script>
	<script type="text/javascript" src="{{ asset('js/dropzone.min.js') }}"></script>
	<script src="{{asset('js/jquery.fancybox.min.js')}}"></script>
	<script src="{{asset('js/slick.min.js')}}"></script>
	<script src="{{asset('js/jquery-ui.min.js')}}"></script>
	<script src="{{asset('js/all.js')}}"></script>
	<script src="{{asset('js/custom.js')}}"></script>
	<script type="text/javascript">
        $(document).ready(function(){
			// add post to fav list
			$('.add-to-fav').click(function(e){
				e.preventDefault();
				if ( $(this).hasClass('auth-block') ) {
					showPopUpMassage(false, "{{ __('messages.authError') }}");
				} else if ( $(this).hasClass('block') ) {
					showPopUpMassage(false, "{{ __('messages.postAddFavPersonal') }}");
				} else {
					var blade = new Object();
					blade['url'] = "#";
					blade['removedMes'] = "{{ __('messages.postRemovedFav') }}";
					blade['addedMes'] = "{{ __('messages.postAddedFav') }}";
					blade['addErrorMes'] = "{{ __('messages.postAddFavError') }}";
					blade['errorMes'] = "{{ __('messages.error') }}";
					addPostToFav($(this), getIdFromClasses($(this).attr("class"), 'id_'), blade);
				}
			});
			//block not-reday links
			$('.not-ready').click(function(e){
				e.preventDefault();
				showPopUpMassage(false, "{{ __('messages.inProgress') }}");
			});
        });
    </script>
    @yield('scripts')
    <noscript>
        <div id="noscript">
            <p>{{__('ui.noscript')}}</p>
        </div>
    </noscript>
</body>
</html>
