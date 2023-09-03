@extends('layouts.page')

@section('meta')
	<title>{{$blog->meta_title}}</title>
	<meta name="description" content="{{$blog->meta_description}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.footerBlog')" i="2" :href="route('blog.index')" />
    <x-bci :text="$blog->title" i="3" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='blog'/>
        <div class="content">
            <article class="article">

                <!--title+author+date-->
                <h1>{{$blog->title}}</h1>
                <p class="blog-misc-info">
                    {{__('ui.by')}}
                    <span>Rigmanager Team</span>,
                    {{__('ui.posted')}}
                    <span>{{$blog->posted_at->toDateString()}}</span>.
                </p>

                @foreach ($blog->tags as $tag)
                    <a href="{{route('blog.index', ['tag'=>$tag])}}" class="catalog-tag">{{$tag}}</a>
                @endforeach

                <!--thumbnail-->
                <img src="{{$blog->thumbnail->url}}" alt="Blog Thumbnail">

                <!--intro+body+outro-->
                <p class="blog-paragraph">{!!$blog->body!!}</p>

                <!--imgs-->
                @if ($blog->images)
                    <p class="blog-sub-header">{{__('ui.attachedImgs')}}</p>
                    <div class="blog-slideshow">
                        <a href="" class="prod-arrow prod-prev"></a>
                        <div class="blog-slider">
                            @foreach ($blog->images as $i => $img)
                                <div class="blog-slider-slide">
                                    <a href="{{$img->url}}" data-fancybox="blogphotos" class="article-img">
                                        <img src="{{$img->url}}" alt="Attached Blog Image {{$i}}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <a href="" class="prod-arrow prod-next"></a>
                    </div>
                @endif

                <!--docs-->
                @if ($blog->documents)
                    <p class="blog-sub-header">{{__('ui.attachedDocs')}}</p>
                    <div class="blog-docs">
                        <ul>
                            @foreach ($blog->documents as $doc)
                                <li>
                                    <span>{{$doc->original_name}}</span>
                                    <a href="{{$doc->url}}" class="blog-doc" download="">{{__('ui.download')}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!--links-->
                @if ($blog->source_name)
                    <p class="blog-sub-header">{{__('ui.attachedLinks')}}</p>
                    <div class="blog-docs">
                        <ul>
                            <li><a href="{{$blog->source_link}}">{{$blog->source_name}}</a></li>
                        </ul>
                    </div>
                @endif

                <!--slg-->
                <p class="blog-paragraph">{{__('ui.blogSlg', ['name'=>'Rigmanager Team'])}}</p>
            </article>
        </div>
	</div>
@endsection

@section('scripts')
    <script src="{{asset('js/blog.js')}}"></script>
@endsection
