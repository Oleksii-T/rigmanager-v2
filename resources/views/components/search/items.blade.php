<div class="catalog">
    @foreach ($posts as $post)
        <div class="catalog-item">
            <!--post-image-->
            <a href="{{route('posts.show', $post)}}" class="catalog-img">
                <img src="{{$post->thumbnail()?->compressed(300) ?? asset('icons/no-image.svg')}}" alt="{{$post->title}}" title="{{$post->title}}">
            </a>
            <!--all post preview but image-->
            <div class="catalog-content">
                <!--title-->
                <div class="catalog-name"><a href="{{route('posts.show', $post)}}">{{$post->title}}</a></div>
                <!--under title line. Lables: type, view, region, date-->
                <div class="catalog-line">
                    <!--type-->
                    <a href="{{route('search', ['types' => [$post->type]])}}" class="catalog-tag">{{\App\Models\Post::typeReadable($post->type)}}</a>
                    <!--add-to-fav-btn-->
                    <a href="{{route('posts.add-to-fav', $post)}}" class="catalog-fav add-to-fav {{$currentUser?->favorites->contains($post) ? 'active' : ''}}">
                        <svg viewBox="0 0 464 424" xmlns="http://www.w3.org/2000/svg">
                            <path class="cls-1" d="M340,0A123.88,123.88,0,0,0,232,63.2,123.88,123.88,0,0,0,124,0C55.52,0,0,63.52,0,132,0,304,232,424,232,424S464,304,464,132C464,63.52,408.48,0,340,0Z"/>
                        </svg>
                    </a>
                    <!--country-->
                    <div class="catalog-lable catalog-region">{{$post->country_readable}}</div>
                    <!--views-->
                    <div class="catalog-lable">{{__('ui.views') . ': ' . $post->views_count}}</div>
                    <!--date-->
                    <div class="catalog-date">{{$post->created_at->diffForHumans()}}</div>
                </div>
                <!--description-->
                <div class="catalog-text">{{$post->description}}</div>
                <!--under description line. Lables: cost, urgent, import-->
                <div class="catalog-line-bottom">
                    <!--price-->
                    <x-post-price :post="$post" class="catalog-price" />
                    <!--urgent+import-->
                    <div>
                        <!--urgent-->
                        @if ($post->is_urgent)
                            <div class="catalog-lable orange">{{__('ui.urgent')}}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
<div class="pagination-field">
    {{$posts->links()}}
</div>
