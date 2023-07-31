@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.post.posts')</title>
	<meta name="description" content="@lang('meta.description.user.post.posts')">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    @if ($category)
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a itemprop="item" href="{{route('profile.favorites')}}"><span itemprop="name">@lang('ui.favourites')</span></a>
            <meta itemprop="position" content="2" />
        </li>
        @foreach ($category?->parents(true)??[] as $category)
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                @if ($loop->last)
                    <span itemprop="name">{{$category->name}}</span>
                @else
                    <a itemprop="item" href="{{route('profile.favorites', $category)}}"><span itemprop="name">{{$category->name}}</span></a>
                @endif
                <meta itemprop="position" content="{{$loop->index + 2}}" />
            </li>
        @endforeach
    @else
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name">@lang('ui.favourites')</span>
            <meta itemprop="position" content="2" />
        </li>
    @endif
@endsection

@section('content')
    <span class="hidden" data-categoryid="{{$category->id??null}}" page-data></span>
    <div class="main-block">
        <x-profile.nav active='fav'/>
        <div class="content">
            <h1>
                @lang('ui.favourites')
                (<span class="orange searched-amount">{{$posts->total()}}</span>)
            </h1>
            @if ($posts->count() == 0)
                <p>{{__('ui.noFavPosts')}}</p>
            @else
                <div class="cabinet-line filter-block">
                    <div class="cabinet-search">
                        <form  method="GET" action="{{route('profile.posts')}}">
                            <fieldset>
                                <input type="text" class="input" placeholder="@lang('ui.search')" name="search">
                                <button class="search-button"></button>
                            </fieldset>
                        </form>
                    </div>
                    <div class="select-block">
                        <select class="styled" name="sorting">
                            @foreach (\App\models\Post::getSorts() as $key => $name)
                                <option value="{{$key}}">{{$name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="select-block">
                        <a href="{{route('profile.clear-favs')}}" class="clear-favs orange">Clear All</a> {{-- //! TRANSLATE --}}
                    </div>
                </div>
                @if (count($categories))
                    <div class="sorting">
                        @foreach ($categories as $c)
                            <div class="sorting-col">
                                <a href="{{route('profile.favorites', $c)}}" class="sorting-item">
                                    {{$c->name}}
                                    <span class="sorting-num">{{$c->all_posts_count}}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="searched-content">
                    <x-profile.favorites :posts="$posts"/>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts-filter.js')}}"></script>
    <script src="{{asset('js/posts.js')}}"></script>
@endsection
