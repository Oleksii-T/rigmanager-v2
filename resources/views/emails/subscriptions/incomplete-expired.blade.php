@component('mail::message')
# Hello!

We were unable to process your subscription payment.<br>
So it been terminated.

@component('mail::button', ['url' => $url])
View Subscription
@endcomponent

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "View Subscription" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
@endcomponent
@endcomponent
