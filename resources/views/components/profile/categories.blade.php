<div class="sorting">
    @foreach ($categories as $category)
        <div class="sorting-col">
            <a href="{{route($categRoute, $category->parents(true))}}" class="sorting-item dynamic-url-params">
                {{$category->name}}
                <span class="sorting-num">{{$category->all_posts_count}}</span>
            </a>
        </div>
    @endforeach
</div>
