@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.catalog')</title>
	<meta name="description" content="@lang('meta.description.catalog')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
	<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<span itemprop="name">@lang('ui.categories')</span>
		<meta itemprop="position" content="2" />
	</li>
@endsection

@section('content')
    <div class="main-block">
        <aside class="side">
            <a href="#side-block" data-fancybox class="side-mob">@lang('ui.catalogNav')</a>
            <div class="side-block" id="side-block">
                <div class="side-title">@lang('ui.catalogNav')</div>
                <ul class="side-list">
                    @foreach ($categories as $category)
                        <li><a href="{{route('search.category', $category)}}">{{$category->name}}</a></li>
                    @endforeach
                </ul>
            </div>
        </aside>
        <div class="content">
            <h1>@lang('ui.categories')</h1>
            <div class="content-top-text catalog-help">@lang('ui.catalogHelp')</div>
            <div class="category">
                @foreach ($categories as $category)
                    <div class="category-col">
                        <div class="category-item">
                            <div class="category-img"><a href="{{route('search.category', $category)}}"><img src="{{$category->image->url}}" alt=""></a></div>
                            <div class="category-name"><a href="{{route('search.category', $category)}}">{{$category->name}}</a> (<span class="orange">{{$category->postsAll()->visible()->count()}}</span>)</div>
                            @if ($category->childs->isNotEmpty())
                                <ul class="category-list">
                                    @foreach ($category->childs as $childCat)
                                        <li>
                                            <a href="{{route('search.category', $childCat)}}">{{$childCat->name}}</a>
                                            @if ($childCat->childs->isNotEmpty())
                                                @foreach ($childCat->childs as $childChildCat)
                                                    <ul class="category-sublist">
                                                        <li><a href="{{route('search.category', $childChildCat)}}">{{$childChildCat->name}}</a></li>
                                                    </ul>
                                                @endforeach
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
