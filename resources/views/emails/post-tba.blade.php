@component('mail::message')
# Hello!

Your Post "{{$post->title}}" has recieved price request from {{$user->name}} ({{$user->getEmails()[0]}})

Be sure to reply your new client!

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you no longer wish to recieve tba requests for this post, please, uncheck "cost TBA" checkbox at post`s settings: <a href="{{route('posts.edit', $post)}}">{{route('posts.edit', $post)}}</a> |
@endcomponent
@endcomponent
