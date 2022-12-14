@component('mail::message')
# Hello!

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

This password reset link will expire in 60 minutes.

If you did not request a password reset, no further action is required.

Regards,<br>
{{ config('app.name') }}

@component('mail::table')
||
|-|
|If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser: <a style="word-break: break-all" href="{{$url}}">{{$url}}</a>|
@endcomponent
@endcomponent
