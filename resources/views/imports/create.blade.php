@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.post.create.im')}}</title>
	<meta name="description" content="{{__('meta.description.post.create.im')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.postImport')" i="2"  islast="1" />
@endsection


@section('style')
    <style>
        .form-block {
            margin-bottom: 0px;
        }
        .lines-to-import-block {
            align-items: baseline;
        }
        .lines-to-import-block label {
            padding-right: 10px;
        }
        .lines-to-import-block input {
            width: auto;
            max-width: 80px;
        }
        .lines-to-import-block .separator {
            padding: 0px 5px;
        }

        .import-column-config {
            align-items: baseline;
        }
        .import-column-config .select-block{
            margin-bottom: 20px;
        }
        .import-column-config p{
            margin-bottom: 0px;
        }
        .import-column-config_heading p {
            color: #fff;
            margin-bottom: 15px;
        }

        .import-top-text {
            justify-content: space-between;
        }
        .back-to-file {
            font-size: 90%;
            cursor: pointer;
            text-decoration: underline
        }

        /* help icon */
        .help-tooltip-icon {
            width: 18px;
            height: 18px;
            display: inline-block;
        }
        .help-tooltip-icon svg{
            vertical-align:text-bottom;
        }
        .help-tooltip-icon svg path {
            transition: all .3s linear;
        }
        .help-tooltip-icon:hover svg path{
            fill: #fff;
        }

        /* column select alert */
        .select-block.has-error .ui-selectmenu-button span.ui-selectmenu-text{
            border: 1px solid red;
        }

        .import-validation-errors {
            margin-top: 15px !important;
            line-height: 28px !important;
            font-size: 90% !important;
        }
    </style>
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='import'/>
        <div class="content">
            <h1>{{__('ui.postImport')}}</h1>
            <div class="import">
                <div class="import-top">
                    <div class="import-top-text wrap-text">{{__('ui.postImportTitle')}}</div>
                    <form id="form-prep-import" action="{{route('imports.prep-store')}}" method="post">
                        @csrf
                        <div class="form-button">
                            <div data-input="file" class="form-error"></div>
                            <input id="import-file" type="file" class="d-none" name="file" accept=".xls,.xlsx">
                            <label for="import-file" class="button" type="submit">{{__('ui.selectFile')}}</label>
                        </div>
                    </form>
                </div>
                <div class="import-top d-none">
                    <div class="import-top-text row">
                        <span class="wrap-text">Please configure import for <span class="import-file_name orange"></span></span>
                        <a class="back-to-file white">Back to file selection</a>
                    </div>
                    <form id="form-import" action="{{route('imports.store')}}" method="post" class="show-full-loader">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="row lines-to-import-block">
                                    <label class="label">Select lines with posts to be imported:</label>
                                    <input class="input" name="start_row" type="number" value="1"/>
                                    <span class="separator">-</span>
                                    <input class="input" name="end_row" type="number"/>
                                </div>
                                <div data-input="start_row" class="form-error"></div>
                                <div data-input="end_row" class="form-error"></div>
                            </div>
                        </div>
                        <div class="row import-column-config">
                            <div class="col-6 import-column-config_heading">
                                <p>
                                    Post Field
                                </p>
                            </div>
                            <div class="col-6 import-column-config_heading">
                                <p>
                                    File Column
                                </p>
                            </div>
                            @foreach ($importColumnsValues as $value => $data)
                                <div class="col-6">
                                    <p>
                                        {{$data['name']}}
                                        @if ($data['required'])
                                            <span class="orange">*</span>
                                        @endif
                                        @if ($data['help'])
                                            <span class="help-tooltip-icon" title="{{$data['help']}}">
                                                @svg('icons/info.svg')
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-6">
                                    <div class="select-block">
                                        <select name="columns[{{$value}}]" class="styled import-column-selector" {{$data['required'] ? 'required' : ''}}></select>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-button">
                            <button type="submit" class="button">{{__('ui.startImport')}}</button>
                        </div>
                        <div data-input="columns" class="import-validation-errors form-error"></div>
                    </form>
                </div>
                <div class="import-bottom">
                    <div class="import-bottom-title">{{__('ui.importHow?')}}</div>
                    <div class="import-bottom-text">
                        <p>{{__('ui.postImportHow')}}</p>
                        <p>All possible categories names and codes can be views on <a href="{{route('categories', ['show_codes' => 1])}}">Categories page</a></p>
                        <p>{{__('ui.downloadExampleImportFile')}} <a href="{{route('imports.download-example')}}">{{__('ui.importFileExample')}}</a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let file = null;
        $(document).ready(function () {
            $('.back-to-file').click(function(e) {
                e.preventDefault();
                $('.import-top').toggleClass('d-none');
            })

            // check required column select and calumn dublicates
            $('.import-column-selector').on('selectmenuchange', function() {
                let map = {};
                $('.import-column-selector').closest('.select-block').removeClass('has-error');
                $('.import-column-selector').each(function(index) {
                    let name = $(this).attr('name');
                    let val = $(this).val();
                    if (map[val]) {
                        map[val].push(name);
                    } else {
                        map[val] = [name];
                    }

                    if (!val && $(this).is('[required]')) {
                        console.log(` ${name} is required!`); //! LOG
                        $(this).closest('.select-block').addClass('has-error');
                    }
                })

                for (const val in map) {
                    let names = map[val];

                    if (!val || names.length <= 1) {
                        continue;
                    }

                    names.forEach(name => {
                        let s = `[name="${name}"]`;
                        $(s).closest('.select-block').addClass('has-error')
                    });
                }
            })

            $('#form-prep-import').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let button = $(this).find('button[type=submit]');
                let formData = new FormData(this);
                file = formData.get('file');
                if (button.hasClass('cursor-wait')) {
                    return;
                }
                fullLoader();
                ajaxSubmit(form, formData, button, function (response) {
                    fullLoader(false);
                    $('.import-top').toggleClass('d-none'); // change view
                    $('.import-file_name').html(response.data.name); // show file name
                    $('[name="start_row"]').val(1); // reset start row input
                    $('[name="end_row"]').val(response.data.total_rows); // set end row input
                    let selectors = $('.import-column-selector');
                    selectors.empty(); // remove all options from columns selectors
                    selectors.selectmenu('destroy').selectmenu({ style: 'dropdown' });
                    selectors.append(`<option value="">-</option>`);
                    let selectAsDefault = 0;
                    let column_keys = Object.keys(response.data.column_names);
                    selectors.each(function(index) {
                        // append file columns as options to selectors
                        for (const key in response.data.column_names) {
                            $(this).append(`<option value="${key}">${response.data.column_names[key]}</option>`);
                        }

                        // select default ciolumn for each selector
                        let valueToSelect = column_keys[selectAsDefault]??'';
                        $(this).val(valueToSelect).selectmenu('refresh').trigger('selectmenuchange');
                        selectAsDefault++;
                    });
                });
            })

            $('#form-import').submit(function(e) {
                e.preventDefault();
                let form = $(this);
                let button = $(this).find('button[type=submit]');
                let formData = new FormData(this);
                formData.append('file', file);
                if (button.hasClass('cursor-wait')) {
                    return;
                }
                fullLoader();
                ajaxSubmit(form, formData, button);
            })
        });
    </script>
@endsection
