@foreach ($posts as $post)
    <div class="ad-col id_{{$post->id}}">
        <div class="ad-item">
            <div class="ad-img">
                <a href="{{route('posts.show', $post)}}">
                    <img src="{{$post->thumbnail()?->compressed(300) ?? asset('icons/no-image.svg')}}" alt="{{$post->title}}">
                </a>
                <a href="{{route('posts.add-to-fav', $post)}}" class="catalog-fav add-to-fav {{($currentUser && $currentUser->favorites->contains($post)) ? 'active' : ''}}">
                    <svg viewBox="0 0 464 424" xmlns="http://www.w3.org/2000/svg" class="">
                        <path class="cls-1" d="M340,0A123.88,123.88,0,0,0,232,63.2,123.88,123.88,0,0,0,124,0C55.52,0,0,63.52,0,132,0,304,232,424,232,424S464,304,464,132C464,63.52,408.48,0,340,0Z"/>
                    </svg>
                </a>
            </div>
            <div class="ad-line">
                <div class="ad-date">{{$post->created_at->diffForHumans()}}</div>
                <a href="{{route('search', ['type'=>$post->type])}}" class="ad-tag">{{$post->type->readable()}}</a>
            </div>
            <div class="ad-title"><a href="{{route('posts.show', $post)}}">{{$post->title}}</a></div>
            @if ($post->region)
                <div class="ad-region">{{$post->region_readable}}</div>
            @endif
            @if ($post->is_import)
                <div class="ad-import">
                    @lang('ui.import')
                </div>
            @endif
            <div class="ad-price">{{$post->cost_readable}}</div>
        </div>
    </div>
@endforeach
