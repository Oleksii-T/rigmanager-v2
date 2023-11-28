@component('mail::message')
# Hello!

We have received {{$plan}} subscription payment.<br>
So it been activated.

@component('mail::button', ['url' => $url])
View Subscription
@endcomponent

Thanks for choosing us.<br>
Do not hesitate to  <a href="{{route('feedbacks.create')}}">contact us</a> if you have any questions.

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "View Subscription" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
@endcomponent
@endcomponent
