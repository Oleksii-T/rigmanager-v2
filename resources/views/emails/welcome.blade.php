@component('mail::message')
# Welcome to rigmanagers.com, {{$user->name}}

What we are suggesting:
- <a target="_blank" href="{{route('search')}}">Global Ol&Gas Equipment listing</a>;
- <a target="_blank" href="{{route('search')}}">Searching</a>,
  <a target="_blank" href="{{route('categories')}}">Categorisation</a>,
  <a target="_blank" href="{{route('profile.favorites')}}">Favourites</a>
  systems;
- Equipment Pricing;
- Sellers contacts;
- Build in price requests;
- <a target="_blank" href="{{route('mailers.index')}}">Mailer</a>;
- <a target="_blank" href="{{route('posts.create')}}">Straightforward equipment publishing</a>;
- <a target="_blank" href="{{route('imports.index')}}">Bulk import</a>.

You may find more information on <a target="_blank" href="{{route('about')}}">About us</a> or <a target="_blank" href="{{route('faq')}}">FAQ</a> pages.<br>
Our legal pages: <a target="_blank" href="{{route('terms')}}">Terms of Service</a> and <a target="_blank" href="{{route('privacy')}}">Privacy Policy</a>.

@component('mail::button', ['url' => route('profile.index')])
Go to profile
@endcomponent

We are confident our service will optimize your business processes.<br>
If there is any question\suggestions\complaints, please, <a target="_blank" href="{{route('feedbacks.create')}}">contact us</a>.

Regards,<br>
{{ config('app.name') }}

{{--
@component('mail::table')
||
|-|
|Some functionalities described above require a payment to be used - <a target="_blank" href="{{route('plans.index')}}">see subscriptions</a>.|
@endcomponent
--}}
@endcomponent
