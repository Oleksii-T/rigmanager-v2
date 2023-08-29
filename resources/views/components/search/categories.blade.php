<div class="sorting">
    @foreach ($categories as $category)
        @php
            $count = $category->postsAll()->visible()->filter($filters)->count();
        @endphp
        @if (!$count)
            @continue
        @endif
        <div class="sorting-col">
            <a href="{{$category->getUrl(true)}}" class="sorting-item dynamic-url-params">
                {{$category->name}}
                <span class="sorting-num">{{$count}}</span>
            </a>
        </div>
    @endforeach
</div>
