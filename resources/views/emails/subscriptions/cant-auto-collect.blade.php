@component('mail::message')
# Hello!

Payment for your {{$plan}} Subscription can not be automatically collected!<br>
It can occur if your payment method is protected by additional security checks.<br>
<br>
Please view Subscriptions page for more details.

@component('mail::button', ['url' => $url])
View Subscription
@endcomponent

Thanks for choosing us.<br>
If there is any uncertainties with the payment, be sure to <a href="{{route('feedbacks.create')}}">contact us</a>.

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "View Subscription" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
@endcomponent
@endcomponent
