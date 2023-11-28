@component('mail::message')
# Hello!

{{$plan}} Subscription was successfully extended!

@component('mail::button', ['url' => $url])
View Subscription
@endcomponent

Thanks for choosing us.<br>
Do not hesitate to  <a href="{{route('feedbacks.create')}}">contact us</a> if you have any questions.

@if ($notPayed)
@component('mail::panel')
Error: we did not receive the payment. One day of payment precessing is allowed. See profile for more details.
@endcomponent
@endif

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "View Subscription" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
@endcomponent
@endcomponent
