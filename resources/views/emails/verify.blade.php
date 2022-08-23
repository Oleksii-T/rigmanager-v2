@component('mail::message')
# Hello!

Please click the button below to verify your email address.

@component('mail::button', ['url' => $url])
Verify Email Address
@endcomponent

If you did not create an account, no further action is required.

Regards,<br>
{{ config('app.name') }}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
@endcomponent
@endcomponent
