@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.faq')}}</title>
    <meta name="description" content="{{__('meta.description.info.faq')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci text="FAQ" i="2" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='faq'/>

		<div class="content">
			<h1>FAQ</h1>
			<div class="content-top-text">{{__('ui.faq.intro')}} <a href="#">{{__('ui.footerContact')}}</a>.</div>
			<div class="faq">
                @foreach ($faqs as $faq)
                    <div class="faq-item">
                        <a href="" id="{{$faq->slug}}" class="faq-top">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 255.99 511.99">
                                <path d="M253,248.62,18.37,3.29A10.67,10.67,0,1,0,3,18L230.56,256,3,494A10.67,10.67,0,0,0,18.37,508.7L253,263.37A10.7,10.7,0,0,0,253,248.62Z"/>
                            </svg>
                            {{$faq->question}}
                        </a>
                        <div class="faq-hidden">
                            <p>{!!$faq->answer!!}</p>
                        </div>
                    </div>
                @endforeach
			</div>
		</div>
	</div>
@endsection
