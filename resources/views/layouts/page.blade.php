@extends('layouts.app')

@section('page-content')
    <x-header/>

    <div class="full-loader d-none">
        <img src="{{asset('icons/loading.svg')}}" alt="">
    </div>
    <section class="main">
        <div class="holder">
            <ol class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="{{route('index')}}"><span itemprop="name">@lang('ui.home')</span></a>
                    <meta itemprop="position" content="1" />
                </li>
                @yield('bc')
            </ol>

            @yield('content')
        </div>
    </section>
@endsection
