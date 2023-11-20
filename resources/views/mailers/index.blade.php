@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.user.mailer')}}</title>
	<meta name="description" content="{{__('meta.description.user.mailer')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.mailer')" i="2" islast="1" />
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='mailer'/>
        <div class="content">
            <h1>{{__('ui.mailer')}} (<span class="orange">{{$mailers->count()}}</span>)</h1>
            @if ($mailers->isNotEmpty())
                <div class="cabinet-line">
                    <div class="cabinet-search">
                    </div>
                    <div class="cabinet-line-right">
                        <form action="{{route('mailers.destroy-all')}}" method="post" class="general-ajax-submit ask" data-asktitle="Are you sure?" data-asktext="You won't be able to revert this!" data-askno="Cancel" data-askyes="Yes, delete all">{{-- //! TRANSLATE --}}
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cabinet-line-link btn-as-link">{{__('ui.mailerDeleteAll')}}</button>
                        </form>
                        <form action="{{route('mailers.deactivate')}}" method="post" class="general-ajax-submit">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="cabinet-line-link btn-as-link">{{__('ui.mailerDeactivateAll')}}</button>
                        </form>
                    </div>
                </div>
                <div class="mailing">
                    @foreach ($mailers as $m)
                        <div class="mailing-item">
                            <div class="mailing-title">{{$m->title}}</div>
                            <div class="mailing-status current {{$m->is_active ? 'status-active' : 'status-disabled'}}">
                                {{$m->is_active ? __('ui.active') : __('ui.notActive')}}
                            </div>
                            <div class="mailing-status status-passive">
                                <form action="{{route('mailers.toggle', $m)}}" method="post" class="general-ajax-submit">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="cabinet-line-link btn-as-link">{{$m->is_active ? __('ui.deactivate') : __('ui.activete')}}</button>
                                </form>
                            </div>
                            <div class="mailing-info">
                                @if ($m->getFilter('author'))
                                    <div class="mailing-info-item">
                                        <div class="mailing-info-name">{{__('ui.author')}}:</div>
                                        <div class="mailing-info-text"><a href="{{route('search', ['author'=>$m->getFilter('author')])}}">{{$m->getFilter('author', true)->name}}</a></div>
                                    </div>
                                @endif
                                @if ($m->getFilter('category'))
                                    <div class="mailing-info-item">
                                        <div class="mailing-info-name">{{__('ui.tag')}}:</div>
                                        <div class="mailing-info-text">
                                            <ul class="form-category-list">
                                                <li><a href="{{$m->getFilter('category', true)->getUrl()}}">{{$m->getFilter('category', true)->name}}</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                                @if ($m->getFilter('search'))
                                    <div class="mailing-info-item">
                                        <div class="mailing-info-name">{{__('ui.mailerKeyword')}}:</div>
                                        <div class="mailing-info-text">{{$m->getFilter('search')}}</div>
                                    </div>
                                @endif
                                @if ($m->getFilter('conditions'))
                                    <div class="mailing-info-item">
                                        <div class="mailing-info-name">@lang('ui.condition'):</div>
                                        <div class="mailing-info-text">
                                            @foreach ($m->getFilter('conditions') as $t)
                                                {{\App\Models\Post::conditionReadable($t) . ($loop->last ? '' : ',')}}
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if ($m->getFilter('types'))
                                    <div class="mailing-info-item">
                                        <div class="mailing-info-name">@lang('ui.type'):</div>
                                        <div class="mailing-info-text">
                                            @foreach ($m->getFilter('types') as $t)
                                                {{\App\Models\Post::typeReadable($t) . ($loop->last ? '' : ',')}}
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if ($m->getFilter('is_urgent') && count($m->getFilter('is_urgent')) != 2)
                                    <div class="mailing-info-item">
                                        <div class="mailing-info-name">@lang('ui.urgent'):</div>
                                        <div class="mailing-info-text">
                                            {{$m->getFilter('is_urgent')[0] ? trans('ui.yes') : trans('ui.no')}}
                                        </div>
                                    </div>
                                @endif
                                @if ($m->getFilter('country'))
                                    <div class="mailing-info-item">
                                        <div class="mailing-info-name">@lang('ui.country'):</div>
                                        <div class="mailing-info-text">
                                            {{trans("countries.".$m->getFilter('country'))}}
                                        </div>
                                    </div>
                                @endif
                                @if ($m->getFilter('currency') && ($m->getFilter('cost_from') || $m->getFilter('cost_to')))
                                    <div class="mailing-info-item">
                                        <div class="mailing-info-name">{{__('ui.cost')}}:</div>
                                        <div class="mailing-info-text">
                                            @if ($m->getFilter('cost_from') && $m->getFilter('cost_to'))
                                                {{$m->getFilter('cost_from')}} - {{$m->getFilter('cost_to')}}
                                            @elseif ($m->getFilter('cost_from'))
                                                > {{$m->getFilter('cost_from')}}
                                            @elseif ($m->getFilter('cost_to'))
                                                < {{$m->getFilter('cost_from')}}
                                            @endif
                                            {{strtoupper($m->getFilter('currency'))}}
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="bar">
                                <div class="bar-icons">
                                    <a href="{{route('mailers.edit', $m)}}" class="bar-edit">
                                        <svg viewBox="0 0 401 398.99" xmlns="http://www.w3.org/2000/svg">
                                            <path transform="translate(0)" d="M370.11,250.39a10,10,0,0,0-10,10v88.68a30,30,0,0,1-30,30H49.94a30,30,0,0,1-30-30V88.8a30,30,0,0,1,30-30h88.67a10,10,0,1,0,0-20H49.94A50,50,0,0,0,0,88.8V349.05A50,50,0,0,0,49.94,399H330.16a50,50,0,0,0,49.93-49.94V260.37a10,10,0,0,0-10-10"/>
                                            <path transform="translate(0)" d="M376.14,13.16a45,45,0,0,0-63.56,0L134.41,191.34a10,10,0,0,0-2.57,4.39l-23.43,84.59a10,10,0,0,0,12.29,12.3l84.59-23.44a10,10,0,0,0,4.4-2.56L387.86,88.44a45,45,0,0,0,0-63.56Zm-220,184.67L302,52l47,47L203.19,244.86Zm-9.4,18.85,37.58,37.58-52,14.39Zm227-142.36-10.6,10.59-47-47,10.6-10.59a25,25,0,0,1,35.3,0L373.74,39a25,25,0,0,1,0,35.31"/>
                                        </svg>
                                    </a>
                                    <form action="{{route('mailers.destroy', $m)}}" method="post" class="general-ajax-submit" style="text-align: center">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bar-delete">
                                            <svg viewBox="0 0 418.17 512" xmlns="http://www.w3.org/2000/svg">
                                                <path transform="translate(0)" d="M416.88,114.44,405.57,80.55A31.52,31.52,0,0,0,375.63,59h-95V28a28.06,28.06,0,0,0-28-28h-87a28.06,28.06,0,0,0-28,28V59h-95A31.54,31.54,0,0,0,12.6,80.55L1.3,114.44a25.37,25.37,0,0,0,24.06,33.4H37.18l26,321.6A46.54,46.54,0,0,0,109.29,512H314.16a46.52,46.52,0,0,0,46.1-42.56l26-321.6h6.54a25.38,25.38,0,0,0,24.07-33.4M167.56,30h83.06V59H167.56Zm162.8,437a16.36,16.36,0,0,1-16.2,15H109.29a16.36,16.36,0,0,1-16.2-15L67.27,147.84h288.9ZM31.79,117.84l9.27-27.79A1.56,1.56,0,0,1,42.55,89H375.63a1.55,1.55,0,0,1,1.48,1.07l9.27,27.79Z"/>
                                                <path transform="translate(0)" d="m282.52 466h0.79a15 15 0 0 0 15-14.22l14.09-270.4a15 15 0 0 0-30-1.56l-14.08 270.38a15 15 0 0 0 14.2 15.8"/>
                                                <path transform="translate(0)" d="m120.57 451.79a15 15 0 0 0 15 14.19h0.83a15 15 0 0 0 14.16-15.79l-14.75-270.4a15 15 0 1 0-30 1.63z"/>
                                                <path transform="translate(0)" d="M209.25,466a15,15,0,0,0,15-15V180.58a15,15,0,0,0-30,0V451a15,15,0,0,0,15,15"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>
                    {{__('ui.noMailer')}}
                    @if ($category)
                        <a href="{{$category->getUrl()}}#add-to-mailer-ad">{{$category->name}}</a>
                    @endif
                </p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')

@endsection
