@component('mail::message')
# Hello!

Your Post "{{$post->title}}" has recieved price request from "{{$user->email}}"

Be sure to reply your new client!

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you no longer wish to recieve tba requests for this post, please, uncheck "TBA cost" checkbox at post`s settings: <a href="{{route('posts.edit', $post)}}">{{route('posts.edit', $post)}}</a> |
@endcomponent
@endcomponent
