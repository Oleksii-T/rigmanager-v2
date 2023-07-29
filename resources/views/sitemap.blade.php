@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.sitemap')}}</title>
    <meta name="description" content="{{__('meta.description.info.sitemap')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
	<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<span itemprop="name">{{__('ui.footerSiteMap')}}</span>
		<meta itemprop="position" content="2" />
	</li>
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
						<li><a href="{{route('categories')}}">{{__('ui.catalog')}}</a></li>
						<li><a href="{{route('search')}}">{{__('ui.catalog')}}</a></li>
					</ul>
				</li>
				<li><a href="">{{__('ui.stPost')}}</a>
					<ul class="sitemap-sub">
						<li><a href="{{route('posts.create')}}">{{__('ui.addPostEq')}}</a></li>
						<li><a class="not-ready" href="#">{{__('ui.addPostSe')}}</a></li>
						<li><a class="not-ready" href="#">{{__('ui.addPostTe')}}</a></li>
					</ul>
				</li>
				<li><a href="">{{__('ui.stAuth')}}</a>
					<ul class="sitemap-sub">
						<li><a href="{{route('login')}}">{{__('ui.signIn')}}</a></li>
						<li><a href="{{route('auth.social', 'facebook')}}">{{__('ui.signInVia')}} Facebook</a></li>
						<li><a href="{{route('auth.social', 'google')}}">{{__('ui.signInVia')}} Google</a></li>
						<li><a href="{{route('register')}}">{{__('ui.signUp')}}</a></li>
					</ul>
				</li>
				<li><a href="">{{__('ui.stProfile')}}</a>
					<ul class="sitemap-sub">
						<li><a href="{{route('profile.index')}}">{{__('ui.profile')}}</a></li>
						<li><a href="{{route('profile.posts')}}">{{__('ui.myPosts')}}</a></li>
						<li><a href="{{route('profile.favorites')}}">{{__('ui.favourites')}}</a></li>
						<li><a href="{{route('mailers.index')}}">{{__('ui.mailer')}}</a></li>
						<li><a href="#">{{__('ui.mySubscription')}}</a></li>
					</ul>
				</li>
				<li><a href="">{{__('ui.info')}}</a>
					<ul class="sitemap-sub">
						<li><a href="{{route('about')}}">{{__('ui.footerAbout')}}</a></li>
						<li><a href="#">{{__('ui.footerBlog')}}</a></li>
						<li><a href="{{route('terms')}}">{{__('ui.footerTerms')}}</a></li>
						<li><a href="{{route('privacy')}}">{{__('ui.footerPrivacy')}}</a></li>
						<li><a href="{{route('site-map')}}">{{__('ui.footerSiteMap')}}</a></li>
						<li><a href="#">{{__('ui.footerSubscription')}}</a></li>
						<li><a href="#">{{__('ui.footerContact')}}</a></li>
						<li><a href="{{route('import-rules')}}">{{__('postImportRules.title')}}</a></li>
						<li><a href="{{route('faq')}}">FAQ</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
@endsection
