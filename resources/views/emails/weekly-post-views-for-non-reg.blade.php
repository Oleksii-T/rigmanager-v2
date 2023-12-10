Hello {{$user->name}}!<br>
<br>
Your posts in automaticaly created <a href="{{url('')}}">rigmanagers.com</a> account, such as<br>
<br>
@foreach($posts as $post)
- <a href="{{route('posts.show', $post)}}">{{$post->title}}</a><br>
@endforeach
<br>
recieved {{$count}} views during last 7 days!<br>
<br>
If you want to enter the account to manage your posts and contact info,<br>
please, use your personal <a href="{{$regUrl}}">simplified registration form</a> (valid for 2 days).<br>
<br>
Regards,<br>
{{config('app.name')}}<br>
{{env('MAIL_TO_ADDRESS')}}
