<div class="catalog blog">
    @foreach ($blogs as $b)
        <article class="catalog-item">
            <a href="{{route('blog.show', $b)}}" class="catalog-img">
                <img src="{{$b->thumbnail->url}}" alt="Blog thumbnail">
            </a>
            <div class="catalog-content">
                <div class="catalog-name">
                    <a href="{{route('blog.show', $b)}}">{{$b->title}}</a>
                    <span class="blog-date"></span>
                </div>
                <div class="catalog-line">
                    @foreach ($b->tags??[] as $tag)
                        <a href="{{route('blog.index', ['tag'=>$tag])}}" class="catalog-tag">{{$tag}}</a>
                    @endforeach
                    @if ($b->country)
                        <div class="catalog-lable catalog-region">{{trans("countries.$b->country")}}</div>
                    @endif
                    <div class="catalog-lable">@lang('ui.views'): {{$b->views_count}}</div>
                    <div class="catalog-date">{{$b->created_at->toDateString()}}</div>
                </div>
                <div class="catalog-line">
                </div>
                <div class="catalog-text">{{$b->sub_title}}</div>
            </div>
        </article>
    @endforeach
</div>
<div class="pagination-field">
    {{$blogs->links()}}
</div>
