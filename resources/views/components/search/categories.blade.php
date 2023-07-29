<div class="sorting">
    @foreach ($categories->get() as $category)
        @php
            $count = $category->postsAll()->visible()->count();
        @endphp
        @if (!$count)
            @continue
        @endif
        <div class="sorting-col">
            <a href="{{route('search.category', $category)}}" class="sorting-item">
                {{$category->name}}
                <span class="sorting-num">{{$count}}</span>
            </a>
        </div>
    @endforeach
</div>
