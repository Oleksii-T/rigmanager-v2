@extends('layouts.page')

@section('meta')
	<title>Limited page</title>
    <meta name="description" content="Limited page">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci text="Limited" i="2" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<div class="content">
			<h1>Some action was prohibited for this account</h1>
            <p>Use the <a href="{{route('feedbacks.create')}}">contact form</a> if you have any questions.</p>
		</div>
	</div>
@endsection
