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
			<article class="policy about-us">
				@lang('ui.about-us-content')
			</article>
		</div>
	</div>
@endsection
