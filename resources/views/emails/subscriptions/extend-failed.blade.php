@component('mail::message')
# Hello!

We faced a payment error when updating your subscription.<br>
So it been terminated.<br>
Please see your profile for more information.

@component('mail::button', ['url' => $url])
View Subscription
@endcomponent

If you have any questions, feel free to <a href="{{route('feedbacks.create')}}">contact us</a>.

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "View Subscription" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
@endcomponent
@endcomponent
