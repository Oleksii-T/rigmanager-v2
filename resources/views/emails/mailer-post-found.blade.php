@component('mail::message')
# Hello!

The newly created post been detected by your <a href="{{route('mailers.edit', $mId)}}">{{$mTitle}}</a> Mailer:

@component('mail::panel')
<ol>
@foreach ($posts as $post)
    <li>
        <a href="{{route('posts.show', $post)}}" target="_blank">{{$post->title}}</a>
    </li>
@endforeach
</ol>
@endcomponent

This email generated automatically by your <a href="{{route('mailers.edit', $mId)}}">{{$mTitle}}</a> Mailer.
<br>
To stop receiving emails from Mailer you may deactivate or delete it.

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If the posts does not meet the given Mailer parameters, please <a href="{{route('feedbacks.create')}}">contact us</a> and describe the issue.|
@endcomponent
@endcomponent
