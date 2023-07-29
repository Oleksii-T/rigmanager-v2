@extends('layouts.page')

@section('meta')
    <title>{{__('meta.title.home')}}</title>
    <meta name="description" content="{{__('meta.description.home')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">{{__('postImportRules.title')}}</span>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    <div class="main-block">
        <x-informations-nav active='xlsx-info'/>

        <div class="content">
            <article class="article import-rules">
                <h1>{{__('postImportRules.title')}}</h1>
                <div class="content-top-text">{{__('postImportRules.intro')}} <a href="{{route('imports.index')}}">{{__('postImportRules.introLink')}}</a> {{__('postImportRules.intro1')}}</div>
                <div class="rule">
                    <div class="rule-icon"><img src="{{asset('icons/xlsx.svg')}}" alt=""></div>
                    <div class="rule-content">
                        <h3>{{__('postImportRules.mainRulesTitle')}}</h3>
                        <ul>
                            <li>{{__('postImportRules.mainRules1')}}</li>
                            <li>{{__('postImportRules.mainRules2')}}</li>
                            <li>{{__('postImportRules.mainRules3')}}</li>
                            <li>{{__('postImportRules.mainRules4')}}</li>
                            <li>{{__('postImportRules.mainRules5')}}</li>
                            <li>{{__('postImportRules.mainRules6')}}</li>
                            <li>{{__('postImportRules.mainRules7')}}</li>
                            <li>{{__('postImportRules.mainRules8')}}</li>
                            <li>{{__('postImportRules.mainRules9')}}</li>
                            <li>{{__('postImportRules.mainRules10')}}</li>
                            <li>{{__('postImportRules.mainRules11')}}</li>
                        </ul>
                    </div>
                </div>
                <div class="article-part"> <!--title-->
                    <h3>{{__('ui.title')}}</h3>
                    <p><span class="white">{{__('postImportRules.required')}}: {{__('ui.yes')}}</span>
                        {{__('postImportRules.titleRule')}}</p>
                </div>
                <div class="article-part"> <!--description-->
                    <h3>{{__('ui.description')}}</h3>
                    <p><span class="white">{{__('postImportRules.required')}}: {{__('ui.yes')}}</span>
                        {{__('postImportRules.titleRule')}}</p>
                </div>
                <div class="article-part"> <!--category-->
                    <h3>{{__('postImportRules.tag')}}</h3>
                    <p><span class="white">{{__('postImportRules.required')}}: {{__('ui.yes')}}</span>
                        {{__('postImportRules.tagRule')}}</p>
                    <div class="article-buttons">
                        <button class="button button-blue show-all-categories-in-popup">{{__('postImportRules.tagRuleEqBtn')}}</button>
                    </div>
                </div>
                <div class="article-part"> <!--images-->
                    <h3>{{__('ui.images')}}</h3>
                    <p><span class="yellow">{{__('postImportRules.required')}}: {{__('ui.no')}}</span>
                        {{__('postImportRules.imagesRule')}}</p>
                </div>
                <div class="article-part"> <!--type-->
                    <h3>{{__('postImportRules.type')}}</h3>
                    <p><span class="white">{{__('postImportRules.required')}}: {{__('ui.yes')}}</span>
                        {{__('postImportRules.typeRule')}}</p>
                </div>
                <div class="article-part"> <!--condition-->
                    <h3>{{__('ui.condition')}}</h3>
                    <p><span class="yellow">{{__('postImportRules.required')}}: {{__('ui.no')}}</span>
                        {{__('postImportRules.conditionRule')}}</p>
                </div>
                <div class="article-part"> <!--amount-->
                    <h3>{{__('ui.amount')}}</h3>
                    <p><span class="yellow">{{__('postImportRules.required')}}: {{__('ui.no')}}</span>
                        {{__('ui.amountHelp')}}</p>
                </div>
                <div class="article-part"> <!--manuf+manuf_date+pn-->
                    <h3>{{__('postImportRules.manufManufDatePN')}}</h3>
                    <p><span class="yellow">{{__('postImportRules.required')}}: {{__('ui.no')}}</span>
                        {{__('postImportRules.manufManufDatePNRule')}}</p>
                </div>
                <div class="article-part"> <!--cost-->
                    <h3>{{__('postImportRules.cost')}}</h3>
                    <p><span class="yellow">{{__('postImportRules.required')}}: {{__('ui.no')}}</span>
                        {{__('ui.costHelp')}}
                        {{__('postImportRules.currencyRule')}}</p>
                </div>
                <div class="article-part"> <!--region-->
                    <h3>{{__('ui.region')}}</h3>
                    <p><span class="yellow">{{__('postImportRules.required')}}: {{__('ui.no')}}</span>
                        {{__('postImportRules.regionRule')}}</p>
                </div>
                <div class="article-part"> <!--lifetime-->
                    <h3>{{__('postImportRules.lifetime')}}</h3>
                    <p><span class="white">{{__('postImportRules.required')}}: {{__('ui.yes')}}</span>
                        {{__('postImportRules.lifetimeRule')}}</p>
                </div>
            </article>
        </div>
    </div>
@endsection

@section('modals')
    <div class="all-categories-as-popup">
        <div class="popup-title">{{__('postImportRules.tagsEqList')}}</div>
        @foreach ($categories as $name => $key)
            <li>{{$name}}: {{$key}}</li>
        @endforeach
    </div>
@endsection
