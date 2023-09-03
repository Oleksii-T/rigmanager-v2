@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.blog')}}</title>
	<meta name="description" content="{{__('meta.description.info.blog')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.footerBlog')" i="2" :href="route('blog.index')" :islast="!request()->tag" />
    @if (request()->tag)
        <x-bci :text="request()->tag" i="3" islast="1" />
    @endif
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='blog'/>
		<div class="content">
			<h1>{{__('ui.footerBlog')}}</h1>
            <div class="searched-content"></div>
		</div>
	</div>
@endsection

@section('scripts')
    <script src="{{asset('js/blog.js')}}"></script>
@endsection
