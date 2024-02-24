@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.sitemap')}}</title>
    <meta name="description" content="{{__('meta.description.info.sitemap')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.footerSiteMap')" i="2" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='sitemap'/>

		<div class="content">
			<h1>{{__('ui.footerSiteMap')}}</h1>
			<ul class="sitemap">
				<li><a href="">{{__('ui.stMain')}}</a>
					<ul class="sitemap-sub">
						<li><a href="{{route('index')}}">{{__('ui.home')}}</a></li>
                        <li><a href="{{route('search')}}">@lang('ui.eqCatalog')</a></li>
                        <li><a href="{{route('search-services')}}">@lang('ui.seCatalog')</a></li>
                        <li><a href="{{route('categories')}}">@lang('ui.eqCategories')</a></li>
                        <li><a href="{{route('categories', 'service')}}">@lang('ui.seCategories')</a></li>
					</ul>
				</li>
				<li><a href="">{{__('ui.stPost')}}</a>
					<ul class="sitemap-sub">
                        <li><a href="{{route('posts.create')}}">@lang('ui.addEqPost')</a></li>
                        <li><a href="{{route('posts.create', 'service')}}">@lang('ui.addSePost')</a></li>
					</ul>
				</li>
                @if ($currentUser)
                    <li><a href="">{{__('ui.stProfile')}}</a>
                        <ul class="sitemap-sub">
                            <li><a href="{{route('profile.index')}}">@lang('ui.profileInfo')</a></li>
                            <li><a href="{{route('profile.posts')}}">@lang('ui.myPosts')</a></li>
                            <li><a href="{{route('profile.favorites')}}">@lang('ui.favourites')</a></li>
                            <li><a href="{{route('imports.index')}}">@lang('ui.imports')</a></li>
                            <li><a href="{{route('mailers.index')}}">@lang('ui.mailer')</a></li>
                            <li><a href="{{route('profile.chat')}}">@lang('ui.chat')</a></li>
                            <li><a href="{{route('notifications.index')}}">@lang('ui.notifications')</a></li>
                            <li><a href="{{route('profile.subscription')}}">@lang('ui.mySubscription')</a></li>
                            <li><a href="{{route('logout')}}" >@lang('ui.signOut')</a></li>
                        </ul>
                    </li>
                @else
                    <li><a href="">{{__('ui.stAuth')}}</a>
                        <ul class="sitemap-sub">
                            <li><a href="{{route('login')}}">{{__('ui.signIn')}}</a></li>
                            <li><a href="{{route('auth.social', 'google')}}">{{__('ui.signInVia')}} Google</a></li>
                            <li><a href="{{route('register')}}">{{__('ui.signUp')}}</a></li>
                        </ul>
                    </li>
                @endif
				<li><a href="">{{__('ui.info')}}</a>
					<ul class="sitemap-sub">
                        <li><a href="{{route('about')}}">{{__('ui.footerAbout')}}</a></li>
                        <li><a href="{{route('blog.index')}}" >{{__('ui.footerBlog')}}</a></li>
                        <li><a href="{{route('terms')}}">{{__('ui.footerTerms')}}</a></li>
                        <li><a href="{{route('privacy')}}">{{__('ui.footerPrivacy')}}</a></li>
                        <li><a href="{{route('site-map')}}">{{__('ui.footerSiteMap')}}</a></li>
                        {{-- <li><a href="{{route('plans.index')}}">{{__('ui.footerSubscription')}}</a></li> --}}
                        <li><a href="{{route('feedbacks.create')}}">{{__('ui.footerContact')}}</a></li>
                        <li><a href="{{route('faq')}}">FAQ</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
@endsection
