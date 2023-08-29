@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.user.post.posts')</title>
	<meta name="description" content="@lang('meta.description.user.post.posts')">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    @if ($category)
        <x-bci :text="trans('ui.myPosts')" i="2" :href="route('profile.posts')" />
        @foreach ($category?->parents(true)??[] as $category)
            <x-bci
                :text="$category->name"
                :href="route('profile.favorites', $category->parents(true))"
                :i="$loop->index + 2"
                :islast="$loop->last"
            />
        @endforeach
    @else
        <x-bci :text="trans('ui.myPosts')" i="2" islast="1" />
    @endif
@endsection

@section('content')
    <span class="hidden" data-categoryid="{{$category->id??null}}" page-data></span>
    <div class="main-block">
        <x-profile.nav active='fav'/>
        <div class="content">
            <h1>
                @lang('ui.favourites')
                (<span class="orange searched-amount">_</span>)
            </h1>
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
            <div class="searched-categories-content"></div>
            <div class="searched-content"></div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts-filter.js')}}"></script>
    <script src="{{asset('js/posts.js')}}"></script>
@endsection
