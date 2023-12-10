Hello {{$user->name}}!<br>
<br>
Your automaticaly created <a href="{{url('')}}">rigmanagers.com</a> account<br>
<br>
recieved {{$count}} profile views during last 24 hours!<br>
<br>
If you want to enter the account to manage your posts and contact info,<br>
please, use your personal <a href="{{$regUrl}}">simplified registration form</a> (valid for 2 days).<br>
<br>
Regards,<br>
{{config('app.name')}}<br>
{{env('MAIL_TO_ADDRESS')}}
