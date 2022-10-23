@extends('layouts.page')

@section('meta')
	<title>{{$user->name}}</title>
	<meta name="description" content="{{$user->name}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{route('search')}}"><span itemprop="name">{{__('ui.catalog')}}</span></a>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    TODO
@endsection
