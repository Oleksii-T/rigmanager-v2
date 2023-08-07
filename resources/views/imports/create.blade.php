@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.post.create.im')}}</title>
	<meta name="description" content="{{__('meta.description.post.create.im')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">{{__('ui.postImport')}}</span>
        <meta itemprop="position" content="2" />
    </li>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='import'/>
        <div class="content">
            <h1>{{__('ui.postImport')}}</h1>
            <div class="import">
                <div class="import-top">
                    <div class="import-top-text">{{__('ui.postImportTitle')}}</div>
                    <form id="form-import" action="{{route('imports.store')}}" method="post" class="general-ajax-submit show-full-loader">
                        @csrf
                        <div class="form-button">
                            <div data-input="file" class="form-error"></div>
                            <input id="import-file" type="file" class="d-none" name="file">
                            <label for="import-file" class="button" type="submit">{{__('ui.selectFileAndPublish')}}</label>
                        </div>
                    </form>
                </div>
                <div class="import-bottom">
                    <div class="import-bottom-title">{{__('ui.importHow?')}}</div>
                    <div class="import-bottom-text">
                        <p>{{__('ui.postImportHow')}}</p>
                        <p>{{__('ui.postImportRules')}} <a href="{{route('import-rules')}}">{{__('postImportRules.title')}}</a>.</p>
                        <p>{{__('ui.importFileLastUpdate')}}: {{env('IMPORT_FILE_UPDATED')}}</p>
                    </div>
                    <a href="{{route('imports.download-example')}}" class="button button-blue">{{__('ui.postImportDownload')}}</a>
                    {{-- <div class="warning">{{__('ui.postImportWarning')}}</div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
