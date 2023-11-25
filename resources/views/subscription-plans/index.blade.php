@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.info.subscription')}}</title>
    <meta name="description" content="{{__('meta.description.info.subscription')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.footerSubscription')" i="2" islast="1" />
@endsection

@section('content')
	<div class="main-block">
		<x-informations-nav active='plans'/>

		<div class="content">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;">
                <h1>{{__('ui.footerSubscription')}}</h1>
                <div class="tumbler">
                    <a href="#" class="tumbler-left plan-interval-toggle active">Monthly</a>
                    <span class="tumbler-block"></span>
                    <a href="#" class="tumbler-right plan-interval-toggle">Yearly</a>
                </div>
            </div>
			<div class="content-top-text">{{__('ui.plansFreeAccessTitle')}}
				<a href="{{route('feedbacks.create')}}">{{__('ui.askAboutPlans')}}</a>
            </div>
			<div class="sub">
				<!--functionality name column-->
				<div class="sub-side">
					<div class="sub-info">
                        <x-subf text="ui.plansBrowse" helpText="ui.plansBrowseHelp" />
                        <x-subf text="ui.plansFav" />
                        <x-subf text="ui.seeCosts" helpText="ui.seeCostsHelp" />
                        <x-subf text="ui.plansContacts" helpText="ui.plansContactsHelp" />
                        <x-subf text="ui.plansPriceRequests" helpText="ui.plansPriceRequestsHelp" />
                        <x-subf text="ui.limitedPublishing" helpText="ui.limitedPublishingHelp" />
                        <x-subf text="ui.plansTranslate" helpFaq="WhatIsAutoTranslator" />
                        <x-subf text="ui.plansMailer" helpFaq="WhatIsMailer" />
                        <x-subf text="ui.plansMessages" helpText="ui.plansMessagesHelp" />
                        <x-subf text="ui.publishPosts" />
                        <x-subf text="ui.plansPostImport" helpFaq="WhatIsImport" />
                        {{-- <x-subf text="ui.plansPostTracking" helpText="ui.plansPostTrackingHelp" /> --}}
					</div>
				</div>
				<!--start column-->
				<div class="sub-col {{!$currentUser || !$currentUser->isSub() ? 'sub-active' : ''}}">
					<div class="sub-top">
						<div class="sub-name">
							<b>Standart</b>
						</div>
						<div class="sub-price">{{__('ui.free')}}</div>
						<div class="sub-text">{{__('ui.plansStartAccHelp')}}</div>
					</div>
					<div class="sub-info">
                        <x-subf text="ui.plansBrowse" helpText="ui.plansBrowseHelp" check="1" />
                        <x-subf text="ui.plansFav" check="1" />
                        <x-subf text="ui.seeCosts" helpText="ui.seeCostsHelp" check="0" />
                        <x-subf text="ui.plansContacts" helpText="ui.plansContactsHelp" check="0" />
                        <x-subf text="ui.plansPriceRequests" helpText="ui.plansPriceRequestsHelp" check="0" />
                        <x-subf text="ui.limitedPublishing" helpText="ui.limitedPublishingHelp" check="0" />
                        <x-subf text="ui.plansTranslate" helpFaq="WhatIsAutoTranslator" check="0" />
                        <x-subf text="ui.plansMailer" helpFaq="WhatIsMailer" check="0" />
                        <x-subf text="ui.plansMessages" helpText="ui.plansMessagesHelp" check="0" />
                        <x-subf text="ui.publishPosts" check="0" />
                        <x-subf text="ui.plansPostImport" helpFaq="WhatIsImport" check="0" />
                        {{-- <x-subf text="ui.plansPostTracking" helpText="ui.plansPostTrackingHelp" check="0" /> --}}
					</div>
					<a href="" class="sub-mob">{{__('ui.details')}}</a>
				</div>
				<!--standart column-->
				<div class="sub-col {{$currentUser?->isSub(1, 'month') ? 'sub-active' : ''}}">
					<div class="sub-top">
						<div class="sub-name">
							<b>{{$plans[1]['month']->title}}</b>
						</div>
						<div class="sub-price interval-toggle">${{$plans[1]['month']->price}} / {{__('ui.month')}}</div>
						<div class="sub-price interval-toggle d-none">${{$plans[1]['year']->price}} / {{__('ui.year')}}</div>
						<div class="sub-text">{{$plans[1]['month']->description}}</div>
					</div>
					<div class="sub-info">
                        <x-subf text="ui.plansBrowse" helpText="ui.plansBrowseHelp" check="1" />
                        <x-subf text="ui.plansFav" check="1" />
                        <x-subf text="ui.seeCosts" helpText="ui.seeCostsHelp" check="1" />
                        <x-subf text="ui.plansContacts" helpText="ui.plansContactsHelp" check="1" />
                        <x-subf text="ui.plansPriceRequests" helpText="ui.plansPriceRequestsHelp" check="1" />
                        <x-subf text="ui.limitedPublishing" helpText="ui.limitedPublishingHelp" check="1" />
                        <x-subf text="ui.plansTranslate" helpFaq="WhatIsAutoTranslator" check="1" />
                        <x-subf text="ui.plansMailer" helpFaq="WhatIsMailer" check="1" />
                        <x-subf text="ui.plansMessages" helpText="ui.plansMessagesHelp" check="1" />
                        <x-subf text="ui.publishPosts" check="0" />
                        <x-subf text="ui.plansPostImport" helpFaq="WhatIsImport" check="0" />
                        {{-- <x-subf text="ui.plansPostTracking" helpText="ui.plansPostTrackingHelp" check="0" /> --}}
					</div>
					<a href="" class="sub-mob">{{__('ui.details')}}</a>
					@if ($currentUser?->isSub(1, 'month'))
						<button href="#" class="sub-button interval-toggle">{{__('ui.chosen')}}</button>
					@else
                        <a href="{{route('plans.show', $plans[1]['month'])}}" class="sub-button interval-toggle">{{__('ui.choose')}}</a>
					@endif
					@if ($currentUser?->isSub(1, 'year'))
						<button href="#" class="sub-button interval-toggle d-none">{{__('ui.chosen')}}</button>
					@else
                        <a href="{{route('plans.show', $plans[1]['year'])}}" class="sub-button interval-toggle d-none">{{__('ui.choose')}}</a>
					@endif
				</div>
				<!--pro column-->
				<div class="sub-col {{$currentUser?->isSub(2, 'month') ? 'sub-active' : ''}}">
					<div class="sub-top">
						<div class="sub-name">
							<b>{{$plans[2]['month']->title}}</b>
						</div>
						<div class="sub-price interval-toggle">${{$plans[2]['month']->price}} / {{__('ui.month')}}</div>
						<div class="sub-price interval-toggle d-none">${{$plans[2]['year']->price}} / {{__('ui.year')}}</div>
						<div class="sub-text">{{$plans[2]['month']->description}}</div>
					</div>
					<div class="sub-info">
                        <x-subf text="ui.plansBrowse" helpText="ui.plansBrowseHelp" check="1" />
                        <x-subf text="ui.plansFav" check="1" />
                        <x-subf text="ui.seeCosts" helpText="ui.seeCostsHelp" check="1" />
                        <x-subf text="ui.plansContacts" helpText="ui.plansContactsHelp" check="1" />
                        <x-subf text="ui.plansPriceRequests" helpText="ui.plansPriceRequestsHelp" check="1" />
                        <x-subf text="ui.limitedPublishing" helpText="ui.limitedPublishingHelp" check="1" />
                        <x-subf text="ui.plansTranslate" helpFaq="WhatIsAutoTranslator" check="1" />
                        <x-subf text="ui.plansMailer" helpFaq="WhatIsMailer" check="1" />
                        <x-subf text="ui.plansMessages" helpText="ui.plansMessagesHelp" check="1" />
                        <x-subf text="ui.publishPosts" check="1" />
                        <x-subf text="ui.plansPostImport" helpFaq="WhatIsImport" check="1" />
                        {{-- <x-subf text="ui.plansPostTracking" helpText="ui.plansPostTrackingHelp" check="1" /> --}}
					</div>
					<a href="" class="sub-mob">{{__('ui.details')}}</a>
					@if ($currentUser?->isSub(2, 'month'))
                        <button href="#" class="sub-button interval-toggle">{{__('ui.chosen')}}</button>
                    @else
                        <a href="{{route('plans.show', $plans[2]['month'])}}" class="sub-button interval-toggle">{{__('ui.choose')}}</a>
					@endif
					@if ($currentUser?->isSub(2, 'year'))
                        <button href="#" class="sub-button interval-toggle d-none">{{__('ui.chosen')}}</button>
                    @else
                        <a href="{{route('plans.show', $plans[2]['year'])}}" class="sub-button interval-toggle d-none">{{__('ui.choose')}}</a>
					@endif
				</div>
			</div>
		</div>
	</div>
@endsection
