@extends('layouts.app')

@section('meta')
    <title>{{__('meta.title.home')}}</title>
    <meta name="description" content="{{__('meta.description.home')}}">
    <meta name="robots" content="index, follow">
@endsection

@section('page-content')
    <div class="header-main">
        <x-header/>
        <section class="top-section">
            <div class="holder">
                <h1>{{__('ui.introduction')}}</h1>
                <div class="top-links">
                    <div class="top-links-item">
                        <a href="#">{{__('ui.introSellEq')}}</a>
                    </div>
                    <div class="top-links-item">
                        <a href="#">{{__('ui.introBuyEq')}}</a>
                    </div>
                    <div class="top-links-item">
                        <a href="#">{{__('ui.introSe')}}</a>
                    </div>
                    <div class="top-links-item hidden">
                        <a class="not-ready" href="#">{{__('ui.introTender')}}</a>
                    </div>
                </div>
                <div class="top-form">
                    <form action="#">
                        <fieldset>
                            <div class="top-form-line">
                                <input type="text" class="input" name="text" placeholder="{{__('ui.search')}}" required>
                                <button class="button">{{__('ui.search')}}</button>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <section class="main-section">
        <div class="holder">
            <div class="main-category">
                <ul class="tabs">
                    <li><a href="#tab1">{{__('ui.equipment')}}</a></li>
                    <li><a href="#tab2">{{__('ui.service')}}</a></li>
                </ul>
                <div id="tab1" class="tab-content">
                    <div class="main-category-block">
                        <ul class="main-category-col">
                            <li><a href="#">{{__('tags.bit')}}</a></li>
                            <li><a href="#">{{__('tags.dp')}}</a></li>
                            <li><a href="#">{{__('tags.rig')}}</a></li>
                            <li><a href="#">{{__('tags.pump')}}</a></li>
                            <li><a href="#">{{__('tags.mud')}}</a></li>
                            <li><a href="#">{{__('tags.boreholeSurvey')}}</a></li>
                            <li><a href="#">{{__('tags.miscHelpEq')}}</a></li>
                            <li><a href="#">{{__('tags.motor')}}</a></li>
                            <li><a href="#">{{__('tags.parts')}}</a></li>
                            <li><a href="#">{{__('tags.control')}}</a></li>
                            <li><a href="#">{{__('tags.stub')}}</a></li>
                        </ul>
                        <ul class="main-category-col">
                            <li><a href="#">{{__('tags.camp')}}</a></li>
                            <li><a href="#">{{__('tags.casingCementing')}}</a></li>
                            <li><a href="#">{{__('tags.emergency')}}</a></li>
                            <li><a href="#">{{__('tags.lubricator')}}</a></li>
                            <li><a href="#">{{__('tags.tubingEq')}}</a></li>
                            <li><a href="#">{{__('tags.wellHeadEq')}}</a></li>
                            <li><a href="#">{{__('tags.packer')}}</a></li>
                            <li><a href="#">{{__('tags.airUtility')}}</a></li>
                            <li><a href="#">{{__('tags.boe')}}</a></li>
                            <li><a href="#">{{__('tags.rotory')}}</a></li>
                            <li><a href="#">{{__('tags.power')}}</a></li>
                            <li><a href="#">{{__('tags.simCasing')}}</a></li>
                        </ul>
                        <ul class="main-category-col">
                            <li><a href="#">{{__('tags.diselStorage')}}</a></li>
                            <li><a href="#">{{__('tags.specMachinery')}}</a></li>
                            <li><a href="#">{{__('tags.lifting')}}</a></li>
                            <li><a href="#">{{__('tags.pipeHandling')}}</a></li>
                            <li><a href="#">{{__('tags.hseEq')}}</a></li>
                            <li><a href="#">{{__('tags.tong')}}</a></li>
                            <li><a href="#">{{__('tags.chemics')}}</a></li>
                            <li><a href="#">{{__('tags.chemLab')}}</a></li>
                            <li><a href="#">{{__('tags.jar')}}</a></li>
                            <li><a href="#">{{__('tags.other')}}</a></li>
                        </ul>

                    </div>
                </div>
                <div id="tab2" class="tab-content">
                    <div class="main-category-block">
                        <ul class="main-category-col">
                            <li><a href="#">{{__('tags.multipleService')}}</a></li>
                            <li><a href="#">{{__('tags.emergencySe')}}</a></li>
                            <li><a href="#">{{__('tags.controll')}}</a></li>
                            <li><a href="#">{{__('tags.drillingCntr')}}</a></li>
                            <li><a href="#">{{__('tags.airWaste')}}</a></li>
                            <li><a href="#">{{__('tags.loggingSe')}}</a></li>
                            <li><a href="#">{{__('tags.ndt')}}</a></li>
                            <li><a href="#">{{__('tags.bitSe')}}</a></li>
                        </ul>
                        <ul class="main-category-col">
                            <li><a href="#">{{__('tags.dhmSe')}}</a></li>
                            <li><a href="#">{{__('tags.grounding')}}</a></li>
                            <li><a href="#">{{__('tags.directionalDrilling')}}</a></li>
                            <li><a href="#">{{__('tags.casingSe')}}</a></li>
                            <li><a href="#">{{__('tags.guard')}}</a></li>
                            <li><a href="#">{{__('tags.bopSe')}}</a></li>
                            <li><a href="#">{{__('tags.training')}}</a></li>
                            <li><a href="#">{{__('tags.pipeShipment')}}</a></li>
                            <li><a href="#">{{__('tags.sellControllFuel')}}</a></li>
                        </ul>
                        <ul class="main-category-col">
                            <li><a href="#">{{__('tags.vihacle')}}</a></li>
                            <li><a href="#">{{__('tags.builders')}}</a></li>
                            <li><a href="#">{{__('tags.loggingSt')}}</a></li>
                            <li><a href="#">{{__('tags.transport')}}</a></li>
                            <li><a href="#">{{__('tags.recyclingSe')}}</a></li>
                            <li><a href="#">{{__('tags.lab')}}</a></li>
                            <li><a href="#">{{__('tags.cementingSe')}}</a></li>
                            <li><a href="#">{{__('tags.otherService')}}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="brand-line">
                <div class="brand-slider">
                    <div class="brand-slide">
                        <a href="#" class="brand-item brand-valid"><img src="{{asset('icons/companies/beiken.jpeg')}}" alt=""></a>
                    </div>
                    <div class="brand-slide">
                        <a href="#" class="brand-item block-link"><img src="{{asset('icons/companies/halliburton.svg')}}" alt=""></a>
                    </div>
                    <div class="brand-slide">
                        <a href="#" class="brand-item block-link"><img src="{{asset('icons/companies/ppc.png')}}" alt=""></a>
                    </div>
                    <div class="brand-slide">
                        <a href="#" class="brand-item block-link"><img src="{{asset('icons/companies/schlumberger.svg')}}" alt=""></a>
                    </div>
                    <div class="brand-slide">
                        <a href="#" class="brand-item block-link"><img src="{{App::isLocale('uk') || App::isLocale('ru') ? asset('icons/companies/ubs-uk.svg') : asset('icons/companies/ubs-en.svg')}}" alt=""></a>
                    </div>
                    <div class="brand-slide">
                        <a href="#" class="brand-item block-link"><img src="{{asset('icons/companies/weatherford.svg')}}" alt=""></a>
                    </div>
                    <div class="brand-slide">
                        <a href="#" class="brand-item block-link"><img src="{{asset('icons/companies/dtek.svg')}}" alt=""></a>
                    </div>
                    <div class="brand-slide">
                        <a href="#" class="brand-item block-link"><img src="{{asset('icons/companies/parker-drilling.png')}}" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="ad-section">
                <h2>{{__('ui.newPosts')}}</h2>
                <div class="ad-list">
                    {{-- $posts --}}
                    <div class="ad-col">
                        <div class="ad-item">
                            <div class="ad-img">
                                <a href="#">
                                    <img src="https://rigmanager.com.ua/storage/2/NLl4OG780GJkACqNFhQRuzzLXQH48e_optimized.png" alt="">
                                </a>
                                <a href="" class="catalog-fav add-to-fav auth-block">
                                    <svg viewBox="0 0 464 424" xmlns="http://www.w3.org/2000/svg">
                                        <path class="cls-1" d="M340,0A123.88,123.88,0,0,0,232,63.2,123.88,123.88,0,0,0,124,0C55.52,0,0,63.52,0,132,0,304,232,424,232,424S464,304,464,132C464,63.52,408.48,0,340,0Z"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="ad-line">
                                <div class="ad-date">1 year ago</div>
                                <a href="#" class="ad-tag">Sell</a>
                            </div>
                            <div class="ad-title">
                                <a href="#">S type or blind rams</a>
                            </div>
                            <div class="ad-import">
                                Import
                            </div>
                            <div class="ad-price">$1,362.00</div>
                        </div>
                    </div>
                    <div class="ad-col ad-col-more">
                        <a href="#" class="ad-more">{{__('ui.morePosts')}}</a>
                    </div>
                </div>
            </div>
            <div class="ad-section">
                <h2>{{__('ui.urgentPosts')}}</h2>
                <div class="ad-list">
                    <div class="ad-col">
                        {{-- $urgent_posts --}}
                        <div class="ad-item">
                            <div class="ad-img">
                                <a href="#">
                                    <img src="https://rigmanager.com.ua/storage/2/NLl4OG780GJkACqNFhQRuzzLXQH48e_optimized.png" alt="">
                                </a>
                                <a href="" class="catalog-fav add-to-fav auth-block">
                                    <svg viewBox="0 0 464 424" xmlns="http://www.w3.org/2000/svg">
                                        <path class="cls-1" d="M340,0A123.88,123.88,0,0,0,232,63.2,123.88,123.88,0,0,0,124,0C55.52,0,0,63.52,0,132,0,304,232,424,232,424S464,304,464,132C464,63.52,408.48,0,340,0Z"></path>
                                    </svg>
                                </a>
                            </div>
                            <div class="ad-line">
                                <div class="ad-date">1 year ago</div>
                                <a href="#" class="ad-tag">Sell</a>
                            </div>
                            <div class="ad-title">
                                <a href="#">S type or blind rams</a>
                            </div>
                            <div class="ad-import">
                                Import
                            </div>
                            <div class="ad-price">$1,362.00</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="main-about">
        <div class="holder">
            <div class="main-about-block">
                <div class="main-about-logo">
                    <img src="{{asset('icons/logo-big.svg')}}" alt="">
                </div>
                <p>{{__('ui.epilogue1')}}</p>
                <p>{{__('ui.epilogue2')}}</p>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

        });
    </script>
@endsection
