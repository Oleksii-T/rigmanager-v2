@component('mail::message')
# Hello!

@if ($created)
The newly created post been detected by your <a href="{{route('mailers.edit', $mId)}}">{{$mTitle}}</a> Mailer:
@else
The re-published post been detected by your <a href="{{route('mailers.edit', $mId)}}">{{$mTitle}}</a> Mailer:
@endif

@component('mail::panel')
# {{$pTitle}}
{{$pDescription}}
@endcomponent

@component('mail::button', ['url' => $url])
See Post
@endcomponent

This email generated automatically by your <a href="{{route('mailers.edit', $mId)}}">{{$mTitle}}</a> Mailer.
<br>
To stop receiving emails from Mailer you may deactivate or delete it.

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "See Post" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
|If the post does not meet the given Mailer parameters, please <a href="{{route('contact-us')}}">contact us</a> and describe the issue.|
@endcomponent
@endcomponent
