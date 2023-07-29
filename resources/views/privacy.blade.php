@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.pp')}}</title>
    <meta name="description" content="{{__('meta.description.info.pp')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('bc')
	<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
		<span itemprop="name">{{__('ui.footerPrivacy')}}</span>
		<meta itemprop="position" content="2" />
	</li>
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='pp'/>

		<div class="content">
			<h1>{{__('ui.footerPrivacy')}}</h1>
			<div class="content-top-text">{{__('ui.content')}}:

                1. <a href="#pp-p1">{{__('privacy.P1')}}</a>
                2. <a href="#pp-p2">{{__('privacy.P2')}}</a>
                3. <a href="#pp-p3">{{__('privacy.P3')}}</a>
                4. <a href="#pp-p4">{{__('privacy.P4')}}</a>
                5. <a href="#pp-p5">{{__('privacy.P5')}}</a>
                6. <a href="#pp-p6">{{__('privacy.P6')}}</a>
                7. <a href="#pp-p7">{{__('privacy.P7')}}</a>
                8. <a href="#pp-p8">{{__('privacy.P8')}}</a>
            </div>
			<article class="policy">
				<ol class="policy-list">
					<li>
						<a id="pp-p1">{{__('privacy.P1')}}</a>
						<ol>
							<li>{{__('privacy.P1.1')}}</li>
							<li>{{__('privacy.P1.2')}}</li>
							<li>{{__('privacy.P1.3')}}</li>
							<li>{{__('privacy.P1.4')}}</li>
							<li>{{__('privacy.P1.5')}}</li>
						</ol>
					</li>
					<li>
						<a id="pp-p2">{{__('privacy.P2')}}</a>
						<ol>
							<li>{{__('privacy.P2.1')}}</li>
							<li>{{__('privacy.P2.2')}}</li>
							<li>{{__('privacy.P2.3')}}</li>
							<li>{{__('privacy.P2.4')}}</li>
						</ol>
					</li>
					<li>
						<a id="pp-p3">{{__('privacy.P3')}}</a>
						<ol>
							<li>{{__('privacy.P3.1')}}</li>
							<li>{{__('privacy.P3.2')}}</li>
							<li>{{__('privacy.P3.3')}}</li>
							<li>{{__('privacy.P3.4')}}</li>
						</ol>
					</li>
					<li>
						<a id="pp-p4">{{__('privacy.P4')}}</a>
						<ol>
							<li>{{__('privacy.P4.1')}}
								<ol>
									<li>{{__('privacy.P4.1.1')}}</li>
									<li>{{__('privacy.P4.1.2')}}</li>
									<li>{{__('privacy.P4.1.3')}}</li>
									<li>{{__('privacy.P4.1.4')}}</li>
									<li>{{__('privacy.P4.1.5')}}</li>
									<li>{{__('privacy.P4.1.6')}}</li>
								</ol>
							</li>
							<li>{{__('privacy.P4.2')}}</li>
						</ol>
					</li>
					<li>
						<a id="pp-p5">{{__('privacy.P5')}}</a>
						<ol>
							<li>{{__('privacy.P5.1')}}</li>
							<li>{{__('privacy.P5.2')}}</li>
							<li>{{__('privacy.P5.3')}}</li>
							<li>{{__('privacy.P5.4')}}</li>
							<li>{{__('privacy.P5.5')}}</li>
							<li>{{__('privacy.P5.6')}}</li>
							<li>{{__('privacy.P5.7')}}</li>
						</ol>
					</li>
					<li>
						<a id="pp-p6">{{__('privacy.P6')}}</a>
						<ol>
							<li>{{__('privacy.P6.1')}}</li>
							<li>{{__('privacy.P6.2')}}</li>
							<li>{{__('privacy.P6.3')}}
								<ol>
									<li>{{__('privacy.P6.3.1')}}</li>
									<li>{{__('privacy.P6.3.2')}}</li>
									<li>{{__('privacy.P6.3.3')}}</li>
									<li>{{__('privacy.P6.3.4')}}</li>
									<li>{{__('privacy.P6.3.5')}}</li>
									<li>{{__('privacy.P6.3.6')}}</li>
									<li>{{__('privacy.P6.3.7')}}</li>
									<li>{{__('privacy.P6.3.8')}}</li>
								</ol>
							</li>
						</ol>
					</li>
					<li>
						<a id="pp-p7">{{__('privacy.P7')}}</a>
						<ol>
							<li>{{__('privacy.P7.1')}}</li>
						</ol>
					</li>
					<li>
						<a id="pp-p8">{{__('privacy.P8')}}</a>
						<ol>
							<li>{{__('privacy.P8.1')}}</li>
							<li>{{__('privacy.P8.2')}}</li>
						</ol>
					</li>
				</ol>
			</article>
		</div>
	</div>
@endsection