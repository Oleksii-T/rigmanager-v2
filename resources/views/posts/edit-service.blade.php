@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.post.create.se')</title>
	<meta name="description" content="@lang('meta.description.post.create.se')">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('ui.myPosts')" :href="route('profile.posts')" i="2" />
    <x-bci :text="$post->title" :href="route('posts.show', $post)" i="3" />
    <x-bci :text="trans('ui.postSettings')" i="4" islast="1" />
@endsection

@section('style')
    <style>
        .categories-level-selects .select-block {
            margin-bottom: 0px;
        }

        /* rick cost input */
        .rci {

        }
        .rci-label{
            display: flex;
        }
        .rci-label .check-block{
            padding-bottom: 0px;
            padding-left: 13px;
        }
        .rci-label .check-item{
            margin-bottom: 0px;
        }
        .rci-label .check-label{
            padding-left: 25px;
        }
        .rci-content {
            display: flex;
            align-items: baseline;
        }
        .rci-input {
            max-width: 100px;
        }
        .rci-separator {
            padding: 0 5px;
            color: white;
        }
    </style>
@endsection

@section('content')
    <span class="d-none" page-data data-images="{{$post->images}}"></span>
    <div class="main-block">
        <div class="content">
            <h1>@lang('ui.postSettings')</h1>
            <div class="form-block">
                <form id="form-post" method="POST" action="{{route('posts.update', $post)}}">
                    @csrf
                    @method('PUT')
                    <fieldset>
                        <!--title+category+cost+type+desc-->
                        <div class="form-section">
                            <label class="label" style="display: flex;justify-content:space-between">
                                <span>
                                    @lang('ui.title')
                                    <span class="orange">*</span>
                                </span>
                            </label>
                            <input class="input input-long" name="title" type="text" value="{{$post->original('title')}}" />
                            <div data-input="title" class="form-error"></div>
                            <div class="form-note lifetime-note-pre">@lang('ui.titleSeHelp')</div>

                            <label class="label">@lang('ui.chooseTag') <span class="orange">*</span></label>
                            <div class="categories-level-selects">
                                <input type="hidden" name="category_id" value="{{$post->category_id}}">
                                <div class="cat-lev-x cat-lev-1">
                                    <div class="select-block">
                                        <select class="styled">
                                            <option value="">@lang('ui.chooseTag')</option>
                                            @foreach ($categsFirstLevel as $c)
                                                <option value="{{$c->id}}" @selected($activeLevels[0] == $c->id)>{{$c->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="cat-lev-x cat-lev-2">
                                    @foreach ($categsSecondLevel as $parentId => $secondLevel)
                                        <div class="select-block {{$activeLevels[0] == $parentId ? '' : 'd-none'}}" data-parentcateg="{{$parentId}}">
                                            <select class="styled">
                                                <option value="">@lang('ui.chooseNextTag')</option>
                                                @foreach ($secondLevel as $c)
                                                    <option value="{{$c->id}}" @selected(($activeLevels[1]??null) == $c->id)>{{$c->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="cat-lev-x cat-lev-3">
                                    @foreach ($categsThirdLevel as $parentId => $thirdLevel)
                                        <div class="select-block  {{($activeLevels[1]??null) == $parentId ? '' : 'd-none'}}" data-parentcateg="{{$parentId}}">
                                            <select class="styled">
                                                <option value="">@lang('ui.chooseNextTag')</option>
                                                @foreach ($thirdLevel as $c)
                                                    <option value="{{$c->id}}" @selected(($activeLevels[2]??null) == $c->id)>{{$c->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div data-input="category_id" class="form-error"></div>
                            <div class="form-note">@lang('ui.tagHelp')</div>

                            <!--cost+type-->
                            <div class="row">
                                <div class="col-6">
                                    <label class="label">@lang('ui.locationRegion')</label>
                                    <div class="select-block">
                                        <select class="select2" name="country" style="width: 100%">
                                            @foreach (countries() as $key => $name)
                                                <option value="{{$key}}" @selected($post->country == $key)>{{$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="add-radio">
                                        <div class="add-radio-col" style="width: 100%">
                                            <label class="label" style="margin-bottom:15px">@lang('ui.choosePostType')<span class="orange">*</span>:</label>
                                            <div class="radio-block">
                                                @foreach (\App\Enums\PostType::forService() as $key => $name)
                                                    <div class="radio-item d-inline-block">
                                                        <input type="radio" name="type" class="radio-input" id="{{$key}}" value="{{$key}}" @checked($post->type->value == $key)>
                                                        <label for="{{$key}}" class="radio-label">{{$name}}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <label class="label">@lang('ui.description') <span class="orange">*</span></label>
                            <textarea cols="30" rows="10" maxlength="9000" class="textarea" name="description" form="form-post">{{$post->original('description')}}</textarea>
                            <div data-input="description" class="form-error"></div>
                            <div class="form-note lifetime-note-pre">@lang('ui.descriptionSeHelp')</div>
                        </div>
                        <div class="form-section row"> <!--images+doc-->
                            <div class="col-6">
                                <label class="label">@lang('ui.image')</label>
                                {{-- file actual input --}}
                                <input type="file" type="file" class="hidden" id="images-multiple-input" multiple>

                                {{-- invisible clone file preview --}}
                                <div class="upload-images_w upload-images_wrapper user-image clone hidden">
                                    <div class="upload-images_label">
                                        @lang('ui.main')
                                    </div>
                                    <div class="upload-images_image">
                                        <div class="upload-images_image-overlay">
                                            <button type="button" class="upload-images_remove">
                                                <svg viewBox="0 0 418.17 512" xmlns="http://www.w3.org/2000/svg">
                                                    <path transform="translate(0)" d="M416.88,114.44,405.57,80.55A31.52,31.52,0,0,0,375.63,59h-95V28a28.06,28.06,0,0,0-28-28h-87a28.06,28.06,0,0,0-28,28V59h-95A31.54,31.54,0,0,0,12.6,80.55L1.3,114.44a25.37,25.37,0,0,0,24.06,33.4H37.18l26,321.6A46.54,46.54,0,0,0,109.29,512H314.16a46.52,46.52,0,0,0,46.1-42.56l26-321.6h6.54a25.38,25.38,0,0,0,24.07-33.4M167.56,30h83.06V59H167.56Zm162.8,437a16.36,16.36,0,0,1-16.2,15H109.29a16.36,16.36,0,0,1-16.2-15L67.27,147.84h288.9ZM31.79,117.84l9.27-27.79A1.56,1.56,0,0,1,42.55,89H375.63a1.55,1.55,0,0,1,1.48,1.07l9.27,27.79Z"></path>
                                                    <path transform="translate(0)" d="m282.52 466h0.79a15 15 0 0 0 15-14.22l14.09-270.4a15 15 0 0 0-30-1.56l-14.08 270.38a15 15 0 0 0 14.2 15.8"></path>
                                                    <path transform="translate(0)" d="m120.57 451.79a15 15 0 0 0 15 14.19h0.83a15 15 0 0 0 14.16-15.79l-14.75-270.4a15 15 0 1 0-30 1.63z"></path>
                                                    <path transform="translate(0)" d="M209.25,466a15,15,0,0,0,15-15V180.58a15,15,0,0,0-30,0V451a15,15,0,0,0,15,15"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <img class="preview" src="" alt="">
                                    </div>
                                </div>

                                {{-- files preview wrapper --}}
                                <div class="upload-images_b upload-images">
                                    @foreach ($post->images as $img)
                                        <div class="upload-images_w upload-images_wrapper user-image" data-index={{$post->images->count() - $loop->index - 1}}>
                                            <div class="upload-images_label">
                                                @lang('ui.main')
                                            </div>
                                            <div class="upload-images_image">
                                                <div class="upload-images_image-overlay">
                                                    <button type="button" class="upload-images_remove" data-id="{{$img->id}}">
                                                        <svg viewBox="0 0 418.17 512" xmlns="http://www.w3.org/2000/svg">
                                                            <path transform="translate(0)" d="M416.88,114.44,405.57,80.55A31.52,31.52,0,0,0,375.63,59h-95V28a28.06,28.06,0,0,0-28-28h-87a28.06,28.06,0,0,0-28,28V59h-95A31.54,31.54,0,0,0,12.6,80.55L1.3,114.44a25.37,25.37,0,0,0,24.06,33.4H37.18l26,321.6A46.54,46.54,0,0,0,109.29,512H314.16a46.52,46.52,0,0,0,46.1-42.56l26-321.6h6.54a25.38,25.38,0,0,0,24.07-33.4M167.56,30h83.06V59H167.56Zm162.8,437a16.36,16.36,0,0,1-16.2,15H109.29a16.36,16.36,0,0,1-16.2-15L67.27,147.84h288.9ZM31.79,117.84l9.27-27.79A1.56,1.56,0,0,1,42.55,89H375.63a1.55,1.55,0,0,1,1.48,1.07l9.27,27.79Z"></path>
                                                            <path transform="translate(0)" d="m282.52 466h0.79a15 15 0 0 0 15-14.22l14.09-270.4a15 15 0 0 0-30-1.56l-14.08 270.38a15 15 0 0 0 14.2 15.8"></path>
                                                            <path transform="translate(0)" d="m120.57 451.79a15 15 0 0 0 15 14.19h0.83a15 15 0 0 0 14.16-15.79l-14.75-270.4a15 15 0 1 0-30 1.63z"></path>
                                                            <path transform="translate(0)" d="M209.25,466a15,15,0,0,0,15-15V180.58a15,15,0,0,0-30,0V451a15,15,0,0,0,15,15"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <img class="preview" src="{{$img->url}}" alt="{{$img->alt}}">
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- file upload block - always visible --}}
                                    <div class="upload-images_w upload-images_wrapper">
                                        <div class="upload-images_e upload-images_empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="33" height="32" viewBox="0 0 33 32" data-testid="add-icon">
                                                <path fill="#7F9799" fill-rule="evenodd" d="M21.43 4l1.325 4h5.67l1.325 1.333v17.334L28.425 28H4.575L3.25 26.667V9.333L4.575 8h5.67l1.325-4h9.86zm-1.91 2.667h-6.04l-1.325 4H5.9v14.666h21.2V10.667h-6.255l-1.325-4zm-3.02 4c3.653 0 6.625 2.99 6.625 6.666S20.153 24 16.5 24s-6.625-2.99-6.625-6.667c0-3.676 2.972-6.666 6.625-6.666zm0 2.666c-2.192 0-3.975 1.794-3.975 4s1.783 4 3.975 4 3.975-1.794 3.975-4-1.783-4-3.975-4z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-note lifetime-note-pre">@lang('ui.imageHelp')</div>
                                <div data-input="images" class="form-error"></div>
                            </div>
                            <div class="col-6">
                                <label class="label">@lang('ui.chooseDoc')</label>

                                {{-- file actual input --}}
                                <input type="file" id="documents-multiple-input" type="file" class="hidden" accept=".pdf,.xls,.xlsx,.xml,.doc,.docx" multiple>

                                {{-- invisible clone file preview --}}
                                <div class="upload-images_w upload-documents_wrapper user-image clone hidden">
                                    <div class="upload-images_image">
                                        <span class="upload-images_name"></span>
                                        <div class="upload-images_image-overlay">
                                            <button type="button" class="upload-documents_remove">
                                                <svg viewBox="0 0 418.17 512" xmlns="http://www.w3.org/2000/svg">
                                                    <path transform="translate(0)" d="M416.88,114.44,405.57,80.55A31.52,31.52,0,0,0,375.63,59h-95V28a28.06,28.06,0,0,0-28-28h-87a28.06,28.06,0,0,0-28,28V59h-95A31.54,31.54,0,0,0,12.6,80.55L1.3,114.44a25.37,25.37,0,0,0,24.06,33.4H37.18l26,321.6A46.54,46.54,0,0,0,109.29,512H314.16a46.52,46.52,0,0,0,46.1-42.56l26-321.6h6.54a25.38,25.38,0,0,0,24.07-33.4M167.56,30h83.06V59H167.56Zm162.8,437a16.36,16.36,0,0,1-16.2,15H109.29a16.36,16.36,0,0,1-16.2-15L67.27,147.84h288.9ZM31.79,117.84l9.27-27.79A1.56,1.56,0,0,1,42.55,89H375.63a1.55,1.55,0,0,1,1.48,1.07l9.27,27.79Z"></path>
                                                    <path transform="translate(0)" d="m282.52 466h0.79a15 15 0 0 0 15-14.22l14.09-270.4a15 15 0 0 0-30-1.56l-14.08 270.38a15 15 0 0 0 14.2 15.8"></path>
                                                    <path transform="translate(0)" d="m120.57 451.79a15 15 0 0 0 15 14.19h0.83a15 15 0 0 0 14.16-15.79l-14.75-270.4a15 15 0 1 0-30 1.63z"></path>
                                                    <path transform="translate(0)" d="M209.25,466a15,15,0,0,0,15-15V180.58a15,15,0,0,0-30,0V451a15,15,0,0,0,15,15"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <img class="preview" src="{{asset('icons/document-icon.png')}}" alt="">
                                    </div>
                                </div>

                                {{-- files preview wrapper --}}
                                <div class="upload-images_b upload-documents">
                                    @foreach ($post->documents as $doc)
                                        <div class="upload-images_w upload-documents_wrapper user-image">
                                            <div class="upload-images_image">
                                                <span class="upload-images_name">{{$doc->original_name}}</span>
                                                <div class="upload-images_image-overlay">
                                                    <button type="button" class="upload-documents_remove" data-id="{{$doc->id}}">
                                                        <svg viewBox="0 0 418.17 512" xmlns="http://www.w3.org/2000/svg">
                                                            <path transform="translate(0)" d="M416.88,114.44,405.57,80.55A31.52,31.52,0,0,0,375.63,59h-95V28a28.06,28.06,0,0,0-28-28h-87a28.06,28.06,0,0,0-28,28V59h-95A31.54,31.54,0,0,0,12.6,80.55L1.3,114.44a25.37,25.37,0,0,0,24.06,33.4H37.18l26,321.6A46.54,46.54,0,0,0,109.29,512H314.16a46.52,46.52,0,0,0,46.1-42.56l26-321.6h6.54a25.38,25.38,0,0,0,24.07-33.4M167.56,30h83.06V59H167.56Zm162.8,437a16.36,16.36,0,0,1-16.2,15H109.29a16.36,16.36,0,0,1-16.2-15L67.27,147.84h288.9ZM31.79,117.84l9.27-27.79A1.56,1.56,0,0,1,42.55,89H375.63a1.55,1.55,0,0,1,1.48,1.07l9.27,27.79Z"></path>
                                                            <path transform="translate(0)" d="m282.52 466h0.79a15 15 0 0 0 15-14.22l14.09-270.4a15 15 0 0 0-30-1.56l-14.08 270.38a15 15 0 0 0 14.2 15.8"></path>
                                                            <path transform="translate(0)" d="m120.57 451.79a15 15 0 0 0 15 14.19h0.83a15 15 0 0 0 14.16-15.79l-14.75-270.4a15 15 0 1 0-30 1.63z"></path>
                                                            <path transform="translate(0)" d="M209.25,466a15,15,0,0,0,15-15V180.58a15,15,0,0,0-30,0V451a15,15,0,0,0,15,15"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <img class="preview" src="{{asset('icons/document-icon.png')}}" alt="">
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- file upload block - always visible --}}
                                    <div class="upload-images_w">
                                        <div class="upload-images_e upload-docs_empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="33" height="33" viewBox="0 0 24 24">
                                                <path fill="#7F9799" fill-rule="evenodd" clip-rule="evenodd" d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V20C21 21.6569 19.6569 23 18 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H18C18.5523 21 19 20.5523 19 20V4C19 3.44772 18.5523 3 18 3ZM6.41421 7H9V4.41421L6.41421 7ZM7 13C7 12.4477 7.44772 12 8 12H16C16.5523 12 17 12.4477 17 13C17 13.5523 16.5523 14 16 14H8C7.44772 14 7 13.5523 7 13ZM7 17C7 16.4477 7.44772 16 8 16H16C16.5523 16 17 16.4477 17 17C17 17.5523 16.5523 18 16 18H8C7.44772 18 7 17.5523 7 17Z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div data-input="documents" class="form-error"></div>
                                <div class="form-note doc-note">@lang('ui.postDocHelp')</div>
                            </div>
                        </div>
                        <div class="form-button-block">
                            <button type="submit" class="button">@lang('ui.update')</button>
                            @if (!$post->is_active)
                            <button type="submit" name="is_active" value="1" class="button">Activate and Publish</button>
                            @endif
                            <a href="{{route('posts.translations.edit', $post)}}" class="button">@lang('ui.editTrans')</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts.js')}}?v={{time()}}"></script>
@endsection
