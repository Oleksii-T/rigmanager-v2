@extends('layouts.app')

@section('page-content')
    <x-header/>

    <div class="full-loader d-none">
        <img src="{{asset('icons/loading.svg')}}" alt="loading">
    </div>
    <section class="main">
        <div class="holder">
            <ol class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
                <x-bci :text="trans('ui.home')" :href="route('index')" i="1"/>
                @yield('bc')
            </ol>

            @yield('content')
        </div>
    </section>
@endsection
