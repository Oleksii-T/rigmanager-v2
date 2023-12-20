@extends('layouts.page')

@section('meta')
	<title>Banned page</title>
    <meta name="description" content="Banned page">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci text="Baned" i="2" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<div class="content">
			<h1>Your account been banned</h1>
            <p>Use the <a href="{{route('feedbacks.create')}}">contact form</a> if you have any questions.</p>
		</div>
	</div>
@endsection
