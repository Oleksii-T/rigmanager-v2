<div class="sorting">
    @foreach ($categories->get() as $category)
        <div class="sorting-col">
            <a href="{{route('search.category', $category)}}" class="sorting-item">
                {{$category->name}}
                <span class="sorting-num">{{$category->postsAll()->visible()->count()}}</span>
            </a>
        </div>
    @endforeach
</div>
