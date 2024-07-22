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
    <div class="prod">
        <div class="prod-content">
            <div class="prod-top">
                <button data-url="{{route('posts.add-to-fav', $post)}}" class="catalog-fav add-to-fav {{$currentUser?->favorites->contains($post) ? 'active' : ''}}">
                    <svg viewBox="0 0 464 424" xmlns="http://www.w3.org/2000/svg">
                        <path class="cls-1" d="M340,0A123.88,123.88,0,0,0,232,63.2,123.88,123.88,0,0,0,124,0C55.52,0,0,63.52,0,132,0,304,232,424,232,424S464,304,464,132C464,63.52,408.48,0,340,0Z"/>
                    </svg>
                    {{__('ui.addToFav')}}
                </button>
            </div>
            <div class="prod-about">
                <h1 class="warning">Post deactivated</h1>
                <p>You may still contact the author to find out the status of the post.</p>
            </div>
        </div>
        <div class="prod-side">
            <x-post-author-block :post="$post" :hasChat="$hasChat" />
        </div>
    </div>
@endsection
