@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.subscription')}}</title>
    <meta name="description" content="{{__('meta.description.info.subscription')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
	<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<span itemprop="name">{{__('ui.footerSubscription')}}</span>
		<meta itemprop="position" content="2" />
	</li>
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='plans'/>


		<div class="content">
			<h1>{{__('ui.footerSubscription')}}</h1>
			@if (Session::has('subscription-required'))
				<div class="content-top-text content-top-error">{{ Session::get('subscription-required') }}</div>
			@endif
			<div class="content-top-text">{{__('ui.plansFreeAccessTitle')}}
				<a href="{{route('faq')}}#cost">{{__('ui.readMoreAboutPlans')}}</a></div>
			<div class="sub">
				<!--functionality name column-->
				<div class="sub-side">
					<div class="sub-info">
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansBrowse')}}</div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansSearch')}}</div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansFilter')}}</div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansFav')}}</div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansTranslate')}} <a href="{{route('faq')}}#WhatIsAutoTranslator">?</a></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansContacts')}}</div>
						</div>
						<div class="sub-info-item with-help">
							<div class="sub-info-text">{{__('ui.publishPosts')}}
							<span>{{__('ui.publishPosts1Help')}}</span></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansMailer')}} <a href="{{route('faq')}}#WhatIsMailer">?</a></div>
						</div>
						<div class="sub-info-item with-help">
							<div class="sub-info-text">{{__('ui.publishPosts')}}
								<span>{{__('ui.publishPosts2Help')}}</span></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.publishTenders')}}</div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansPostImport')}} <a href="{{route('faq')}}#WhatIsImport">?</a></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-info-text">{{__('ui.plansPostTracking')}}</div>
						</div>
					</div>
				</div>
				<!--start column-->
				<div class="sub-col {{auth()->user() && !auth()->user()->is_standart ? 'sub-active' : ''}}">
					<div class="sub-top">
						<div class="sub-name">
							<b>Start</b>
							{{__('ui.account')}}
						</div>
						<div class="sub-price">{{__('ui.free')}}</div>
						<div class="sub-text">{{__('ui.plansStartAccHelp')}}</div>
					</div>
					<div class="sub-info">
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansBrowse')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansSearch')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansFilter')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansFav')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansTranslate')}} <a href="{{route('faq')}}#WhatIsAutoTranslator">?</a></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.plansContacts')}}</div>
						</div>
						<div class="sub-info-item with-help">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.publishPosts')}}
								<span>{{__('ui.publishPosts1Help')}}</span></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.plansMailer')}} <a href="{{route('faq')}}#WhatIsMailer">?</a></div>
						</div>
						<div class="sub-info-item with-help">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.publishPosts')}}
								<span>{{__('ui.publishPosts2Help')}}</span></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.publishTenders')}}</div>
						</div>
						<div class="sub-info-item">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.plansPostImport')}} <a href="{{route('faq')}}#WhatIsImport">?</a></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.plansPostTracking')}}</div>
						</div>
					</div>
					<a href="" class="sub-mob">{{__('ui.details')}}</a>
				</div>
				<!--standart column-->
				<div class="sub-col {{auth()->user() && auth()->user()->is_standart && !auth()->user()->is_pro ? 'sub-active' : ''}}">
					<div class="sub-top">
						<div class="sub-name">
							<b>Standart</b>
							{{__('ui.account')}}
						</div>
						<div class="sub-price">??? ₴ / {{__('ui.mon')}}</div>
						<div class="sub-text">{{__('ui.plansStandartAccHelp')}}</div>
					</div>
					<div class="sub-info">
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansBrowse')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansSearch')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansFilter')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansFav')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansTranslate')}} <a href="{{route('faq')}}#WhatIsAutoTranslator">?</a></div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansContacts')}}</div>
						</div>
						<div class="sub-info-item with-help">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.publishPosts')}}
								<span>{{__('ui.publishPosts1Help')}}</span></div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansMailer')}} <a href="{{route('faq')}}#WhatIsMailer">?</a></div>
						</div>
						<div class="sub-info-item with-help">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.publishPosts')}}
								<span>{{__('ui.publishPosts2Help')}}</span></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.publishTenders')}}</div>
						</div>
						<div class="sub-info-item">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.plansPostImport')}} <a href="{{route('faq')}}#WhatIsImport">?</a></div>
						</div>
						<div class="sub-info-item">
							<div class="sub-no"></div>
							<div class="sub-info-text">{{__('ui.plansPostTracking')}}</div>
						</div>
					</div>
					<a href="" class="sub-mob">{{__('ui.details')}}</a>
					@if (auth()->user() && auth()->user()->is_standart && !auth()->user()->is_pro)
						<button href="" class="sub-button">{{__('ui.chosen')}}</button>
					@else
						<form action="" method="POST">
							@csrf
							<input type="text" name="plan" value="standart" hidden>
							<button href="" class="not-ready sub-button">{{__('ui.plansChoose')}}</button>
						</form>
					@endif
				</div>
				<!--pro column-->
				<div class="sub-col {{auth()->user() && auth()->user()->is_pro ? 'sub-active' : ''}}">
					<div class="sub-top">
						<div class="sub-name">
							<b>Pro</b>
							{{__('ui.account')}}
						</div>
						<div class="sub-price">??? ₴ / {{__('ui.mon')}}</div>
						<div class="sub-text">{{__('ui.plansProAccHelp')}}</div>
					</div>
					<div class="sub-info">
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansBrowse')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansSearch')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansFilter')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansFav')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansTranslate')}} <a href="{{route('faq')}}#WhatIsAutoTranslator">?</a></div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansContacts')}}</div>
						</div>
						<div class="sub-info-item with-help">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.publishPosts')}}
								<span>{{__('ui.publishPosts1Help')}}</span></div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansMailer')}} <a href="{{route('faq')}}#WhatIsMailer">?</a></div>
						</div>
						<div class="sub-info-item with-help">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.publishPosts')}}
								<span>{{__('ui.publishPosts2Help')}}</span></div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.publishTenders')}}</div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansPostImport')}} <a href="{{route('faq')}}#WhatIsImport">?</a></div>
						</div>
						<div class="sub-info-item">
							<img src="{{asset('icons/sub-check.svg')}}" alt="">
							<div class="sub-info-text">{{__('ui.plansPostTracking')}}</div>
						</div>
					</div>
					<a href="" class="sub-mob">{{__('ui.details')}}</a>
                    <a href="{{route('plans.subscribe')}}" class="not-ready sub-button">{{__('ui.choose')}}</a>
				</div>
			</div>
		</div>
	</div>
@endsection
