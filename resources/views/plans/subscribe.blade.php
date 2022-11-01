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
			<h1>TODO</h1>

		</div>
	</div>
@endsection
