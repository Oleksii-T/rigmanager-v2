@extends('layouts.page')

@section('meta')
	<title>{{$user->name}}</title>
	<meta name="description" content="{{$user->name}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.catalog')" :href="route('search')" i="2" />
    <x-bci :text="$user->name" i="3" islast="1"/>
@endsection

@section('content')
    TODO
@endsection
