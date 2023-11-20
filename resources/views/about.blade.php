@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.about-us')}}</title>
	<meta name="description" content="{{__('meta.description.info.about-us')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.footerAbout')" i="2" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='about'/>
		<div class="content">
			<h1>{{__('ui.footerAbout')}}</h1>
			<article class="policy">
				<p class="aboutus-intro">{{__('ui.aboutUsIntro')}}</p>
				<p class="aboutus-body">{{__('ui.aboutUsBody')}}</p>
				<p class="aboutus-body">{{__('ui.aboutUsQ')}} <a href="{{route('faq')}}">{{__('ui.aboutUsQLink')}}</a>.</p>
				<p class="aboutus-body">{{__('ui.aboutUsContact')}} <a href="{{route('feedbacks.create')}}">{{__('ui.aboutUsContactLink')}}</a>.</p>
				<p class="aboutus-body">{{__('ui.aboutUsSlg')}}</p>
			</article>
		</div>
	</div>
@endsection
