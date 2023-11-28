@component('mail::message')
# Hello!

Your previous active {{$plan}} Subscription was canceled due to new subscription!<br>
If you have any questions, feel free to <a href="{{route('feedbacks.create')}}">contact us</a>.

Regards,<br>
{{config('app.name')}}

@endcomponent
