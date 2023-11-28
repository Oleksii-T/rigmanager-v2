@component('mail::message')
# Hello!

Your {{$plan}} subscription been canceled.<br>
Please, consider contacting us if you have any complaints about paid experiance.<br>
Subscription will be active to the end of paid period.

@component('mail::button', ['url' => $url])
Contact Us
@endcomponent

Regards,<br>
{{config('app.name')}}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "Contact Us" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
@endcomponent
@endcomponent
