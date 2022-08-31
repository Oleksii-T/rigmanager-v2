<div class="sorting">
    @foreach ($category->childs()->active()->get() as $category)
        <div class="sorting-col">
            <a href="{{route('categories.show', $category)}}" class="sorting-item">
                {{$category->name}}
                <span class="sorting-num">{{$category->postsAll()->visible()->count()}}</span>
            </a>
        </div>
    @endforeach
</div>
