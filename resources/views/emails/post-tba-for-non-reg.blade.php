Hello {{$currentU->name}}!
<br><br>
Your post ' <a href="{{route('posts.show', $post)}}">{{$post->title}}</a>' in automaticaly created <a href="{{url('')}}">rigmanagers.com</a> account<br>
recieved price request from {{$user->name}} ({{$user->getEmails()[0]}}).
<br><br>
Be sure to reply to your new client.
<br><br>
If you want to enter the account to manage your posts and contact info,<br>
please, use your personal <a href="{{$regUrl}}">simplified registration form</a> (valid for 2 days).
<br><br>
Regards,<br>
{{config('app.name')}}<br>
{{env('MAIL_TO_ADDRESS')}}
