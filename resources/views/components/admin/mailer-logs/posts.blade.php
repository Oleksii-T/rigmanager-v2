{{$posts->count()}}:
@foreach ($posts as $post)
    <a href="{{route('admin.posts.edit', $post)}}">{{$post->title}}</a>@if(!$loop->last),@endif
@endforeach
