@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.about-us')}}</title>
	<meta name="description" content="{{__('meta.description.info.about-us')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.footerAbout')" i="2" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='about'/>
		<div class="content">
			<h1>{{__('ui.footerAbout')}}</h1>
			<article class="policy">
				<p class="aboutus-intro">rigmanagers.com - is a free bulletin board specialized for Oil&Gas procurement processes.</p>
				
				<b>What do we offer?</b>
				<ul style="margin-bottom: 24px">
					<li>Simplifying the procurement process;</li>
					<li>Forging broader, faster, and more effective collaboration between members of your niche;</li>
					<li>Additional opportunity for procurement planning;</li>
					<li>Reduction of potential procurement costs;</li>
					<li>Increasing the level of sales.</li>
				</ul>

				<b>What we are suggesting for <u>sellers</u>?</b>
				<ul style="margin-bottom: 24px">
					<li><a class="orange" href="{{route('posts.create')}}">Free advertising of equipment</a> - no costs applied for placement of our equipment;</li>
					<li>Increasing internet visibility of listings - our platform takes the responsibility to deliver potential buyers traffic;</li>
					<li><a class="orange" href="{{route('imports.create')}}">Bulk posts importing</a>  - for businesses with a wide range of listings;</li>
					<li><a class="orange" href="{{route('feedbacks.create')}}">Support with post creation</a> - we may assist you with category selection, bulk posts importing, and maintaining of your listings.</li>
				</ul>

				<b>What we are suggesting for <u>buyers</u>?</b>
				<ul style="margin-bottom: 24px">
					<li>Structured data - our search, <a class="orange" href="{{route('categories')}}">categorization</a>, filters, and author makes navigation easier;</li>
					<li>Extensive amount of posts - from <a class="orange" href="{{\App\Models\Category::getBySlug('others-spare-parts')?->getUrl()}}">Spare Parts</a> to <a class="orange" href="{{\App\Models\Category::getBySlug('rig-accessories')?->getUrl()}}">Drilling Rigs</a>, we got everything you need;</li>
					<li>Analytics tool - study the market and learn active proposals;</li>
					<li><a class="orange" href="{{route('mailers.index')}}">Smart Mailer feature</a> - will email you if any new post has been created by your request;</li>
					<li>Regular updates - our marketing team constantly contacts new sellers thanks to who our <a class="orange" href="{{route('search')}}">catalog</a> grows bigger.</li>
				</ul>

				<b>Why do we stand out?</b>
				<p class="aboutus-body">We believe that modern technologies can ease communication and save time for procurement specialists. 
					Our goal is to present digital tool that will be effective in local and international markets.
					In addition, our team may propose automation for some of your routine tasks.
				</p>

				<b>Our Team</b>
				<p class="aboutus-body">The core of the rigmanagers.com team spend years in Oil&Gas industry. 
					Now they are trying to solve the existing problem of the market which bothers them during their work experience.
					Meet our founders:
					Pavlo - Head of the Procurement Department;
					Dmitriy - Senior Drilling Engineer (former construction engineer);
					Alexey - Fullstack web developer (former on-site interpreter).
					Besides, the team was expanded by specialists in digital marketing and sales managers which helps us to deliver the solution to the industry.
				</p>

				<p>We do appreciate any feedback and are ready to answer all the questions you may have - <a href="{{route('feedbacks.create')}}">Contact Us</a></p>
			</article>
		</div>
	</div>
@endsection
