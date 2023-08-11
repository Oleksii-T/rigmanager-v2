@extends('layouts.page')

@section('meta')
	<title>@lang('meta.title.post.create.se')</title>
	<meta name="description" content="@lang('meta.description.post.create.se')">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name"></span>
        <a itemprop="item" href="{{route('profile.posts')}}"><span itemprop="name">@lang('ui.myPosts')</span></a>
        <meta itemprop="position" content="2" />
    </li>
    <li class="crop-bc-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <a itemprop="item" href="{{route('posts.show', $post)}}"><span itemprop="name">{{$post->title}}</span></a>
        <meta itemprop="position" content="3" />
    </li>
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
        <span itemprop="name">@lang('ui.postSettings')</span>
        <meta itemprop="position" content="4" />
    </li>
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
                        <div class="form-section"> <!--title+tag-->
                            <label class="label" style="display: flex;justify-content:space-between">
                                <span>
                                    @lang('ui.title') 
                                    <span class="orange">*</span>
                                </span>

                                <div class="check-block">
                                    <div class="check-item">
                                        <input type="checkbox" name="is_urgent" class="check-input" id="ch22" value="1" @checked($post->is_urgent)>
                                        <label for="ch22" class="check-label" style="color: #ffc990">@lang('ui.makePostUrgent')</label>
                                    </div>
                                </div>
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

                            <!--type+role+condition+optionals-->
                            <div class="add-radio">
                                <div class="add-radio-col" style="width: 100%">
                                    <label class="label" style="display: inline-block;padding-right:10px">@lang('ui.choosePostType')<span class="orange">*</span>:</label>
                                    <div class="radio-block" style="display: inline-block">
                                        @foreach (\App\Models\Post::TYPES as $item)
                                            <div class="radio-item" style="display: inline-block">
                                                <input type="radio" name="type" class="radio-input" id="{{$item}}" value="{{$item}}" @checked($post->type == $item)>
                                                <label for="{{$item}}" class="radio-label">{{\App\Models\Post::typeReadable($item)}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="faq-item optionals">
                                <a href="" class="faq-top">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 255.99 511.99">
                                        <path d="M253,248.62,18.37,3.29A10.67,10.67,0,1,0,3,18L230.56,256,3,494A10.67,10.67,0,0,0,18.37,508.7L253,263.37A10.7,10.7,0,0,0,253,248.62Z"/>
                                    </svg>
                                    <span class="text-show">@lang('ui.showOptionals')</span>
                                </a>
                                <div class="faq-hidden">
                                    <p>@lang('ui.optionalsHelp')</p>

                                    <label class="label">@lang('ui.chooseAmount')</label>
                                    <input class="input" name="amount" type="text" value="{{$post->amount}}"/>
                                    <div data-input="amount" class="form-error"></div>
                                    <div class="form-note">@lang('ui.amountHelp')</div>

                                    <label class="label">@lang('ui.locationRegion')</label>
                                    <div class="select-block">
                                        <select class="select2" name="country">
                                            @foreach (countries() as $key => $name)
                                                <option value="{{$key}}" @selected($post->country == $key)>{{$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label class="label">@lang('ui.chooseManufacturer')</label>
                                    <input class="input" name="manufacturer" type="text" value="{{$post->manufacturer}}"/>
                                    <div data-input="manufacturer" class="form-error"></div>

                                    <label class="label">@lang('ui.chooseManufacturedDate')</label>
                                    <input class="input" name="manufacture_date" type="text" value="{{$post->manufacture_date}}"/>
                                    <div data-input="manufacture_date" class="form-error"></div>

                                    <label class="label">@lang('ui.choosePartNum')</label>
                                    <input class="input" name="part_number" type="text" value="{{$post->part_number}}"/>
                                    <div data-input="part_number" class="form-error"></div>

                                    <label class="label">@lang('ui.currency')</label> {{-- //! TRANSLATE --}}
                                    <div class="select-block">
                                        <select class="styled" name="currency">
                                            @foreach (currencies() as $key => $symbol)
                                                <option value="{{$key}}" @selected($post->currency == $key)>{{strtoupper($key)}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <label class="label">@lang('ui.chooseCondition')</label>
                                    <div class="select-block">
                                        <div class="radio-block">
                                            @foreach (\App\Models\Post::CONDITIONS as $item)
                                                <div class="radio-item">
                                                    <input type="radio" name="condition" class="radio-input" id="{{$item}}" value="{{$item}}" @checked($post->condition == $item)>
                                                    <label for="{{$item}}" class="radio-label">{{\App\Models\Post::conditionReadable($item)}}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <label class="label">@lang('ui.cost')</label>
                                    <input class="input format-cost" name="cost" type="text" placeholder="@lang('ui.cost')}}" value="{{$post->cost}}"/>
                                    <div data-input="cost" class="form-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-section"> <!--description-->
                            <label class="label">@lang('ui.description') <span class="orange">*</span></label>
                            <textarea cols="30" rows="10" maxlength="9000" class="textarea" name="description" form="form-post">{{$post->original('description')}}</textarea>
                            <div data-input="description" class="form-error"></div>
                            <div class="form-note lifetime-note-pre">@lang('ui.descriptionSeHelp')</div>
                        </div>
                        <div class="form-section"> <!--images+doc-->
                            <label class="label">@lang('ui.image')</label>
                            <input type="file" type="file" class="hidden" id="images-multiple-input" multiple>
                            <div class="upload-images_wrapper user-image clone hidden">
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
                            <div class="upload-images">
                                @foreach ($post->images as $img)
                                    <div class="upload-images_wrapper user-image" data-index={{$post->images->count() - $loop->index - 1}}>
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
                                <div class="upload-images_wrapper">
                                    <div class="upload-images_empty">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="33" height="32" viewBox="0 0 33 32" data-testid="add-icon"><path fill="#7F9799" fill-rule="evenodd" d="M21.43 4l1.325 4h5.67l1.325 1.333v17.334L28.425 28H4.575L3.25 26.667V9.333L4.575 8h5.67l1.325-4h9.86zm-1.91 2.667h-6.04l-1.325 4H5.9v14.666h21.2V10.667h-6.255l-1.325-4zm-3.02 4c3.653 0 6.625 2.99 6.625 6.666S20.153 24 16.5 24s-6.625-2.99-6.625-6.667c0-3.676 2.972-6.666 6.625-6.666zm0 2.666c-2.192 0-3.975 1.794-3.975 4s1.783 4 3.975 4 3.975-1.794 3.975-4-1.783-4-3.975-4z"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="form-note lifetime-note-pre">@lang('ui.imageHelp')</div>
                            <div data-input="images" class="form-error"></div>
                            <label class="label">@lang('ui.chooseDoc')</label>
                            <div class="edit-doc-button">
                                <input type="file" id="documents-multiple-input" type="file" class="hidden" accept=".pdf,.xls,.xlsx,.xml,.doc,.docx" multiple>
                                <label for="documents-multiple-input" class="edit-doc-label">@lang('ui.chooseFile')</label>
                            </div>
                            <div class="upload-documents_wrapper hidden clone">
                                <span class="doc-name"></span>
                                <span class="upload-documents_remove">Remove</span>
                            </div>
                            <div class="upload-documents">
                                @foreach ($post->documents as $doc)
                                    <div class="upload-documents_wrapper">
                                        <span class="doc-name">{{$doc->original_name}}</span>
                                        <span class="upload-documents_remove" data-id="{{$doc->id}}">Remove</span>
                                    </div>
                                @endforeach
                            </div>
                            <div data-input="documents" class="form-error"></div>
                            <div class="form-note doc-note">@lang('ui.postDocHelp')</div>
                        </div>
                        <div class="form-section"> <!--lifetime+special-->
                            <label class="label">@lang('ui.chooseActiveTo') <span class="orange">*</span></label>
                            <div class="select-block">
                                <select class="styled" name="duration">
                                    @foreach (\App\Models\Post::DURATIONS as $item)
                                        <option value="{{$item}}" @selected($post->duration == $item)>{{\App\Models\Post::durationReadable($item)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div data-input="duration" class="form-error"></div>
                        </div>
                        <div class="form-button-block">
                            <button type="submit" class="button">@lang('ui.update')</button>
                            <a  href="{{route('posts.translations.edit', $post)}}" class="button">@lang('ui.editTrans')</a>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/posts.js')}}"></script>
@endsection
