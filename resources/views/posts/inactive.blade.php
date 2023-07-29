@extends('layouts.page')

@section('meta')
	<title>{{$post->title . ' - ' . $post->category->name . ' ' . __('meta.title.post.show')}}</title>
	<meta name="description" content="{{$post->cost_readable . ': ' . (strlen($post->description)>90 ? substr($post->description, 0, 90) . '...' : $post->description)}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{route('search')}}"><span itemprop="name">{{__('ui.catalog')}}</span></a>
        <meta itemprop="position" content="2" />
    </li>
    @foreach ($post->category->parents(true) as $category)
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="{{route('search.category', $category)}}"><span itemprop="name">{{$category->name}}</span></a>
            <meta itemprop="position" content="{{$loop->index+3}}" />
        </li>
        @if ($loop->last)
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span itemprop="name">{{$post->title}}</span>
                <meta itemprop="position" content="{{$loop->index+4}}" />
            </li>
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
