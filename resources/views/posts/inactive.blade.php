@extends('layouts.page')

@section('meta')
	<title>{{$post->meta_title}}</title>
	<meta name="description" content="{{$post->meta_description}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.catalog')" :href="route('search')" i="2"/>
    @foreach ($post->category->parents(true) as $category)
        <x-bci :text="$category->name" :href="$category->getUrl()" :i="$loop->index+3"/>
        @if ($loop->last)
            <x-bci :text="$post->title" :i="$loop->index+4" islast="1"/>
        @endif
    @endforeach
@endsection

@section('content')
    <div class="main-block">
        <div class="content inactive-prod">
            <h1>{{__('ui.postInactiveError')}}</h1>
        </div>
    </div>
@endsection
