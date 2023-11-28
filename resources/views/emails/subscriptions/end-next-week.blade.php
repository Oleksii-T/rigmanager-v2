@component('mail::message')
# Hello!

This is reminder that your<br>
@if ($isCanceled)
{{$plan}} subscription ends in 7 days.<br>
@else
{{$plan}} subscription will be renewed in 7 days.<br>
@endif

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