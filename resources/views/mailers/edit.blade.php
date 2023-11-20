@extends('layouts.page')

@section('meta')
	<title>{{__('meta.title.user.mailer-edit')}}</title>
	<meta name="description" content="{{__('meta.description.user.mailer-edit')}}">
    <meta name="robots" content="noindex, nofollow">
@endsection

@section('bc')
    <x-bci :text="trans('mailers.index')" :href="{{route('')}}" i="2" />
    <x-bci :text="trans('ui.editing')" i="3" islast="1" />
@endsection

@section('content')
    <div class="main-block">
        <x-profile.nav active='mailer'/>
        <div class="content">
            <h1>{{__('ui.editing')}} "{{$mailer->title}}"</h1>
            <div class="form-block">
                <form action="{{route('mailers.update', $mailer)}}" method="POST" id="form-mailer" class="general-ajax-submit">
                    @csrf
                    @method('PATCH')
                    <fieldset>
                        <div class="form-section">
                            <label class="label">@lang('ui.mailerTitle') <span class="orange">*</span></label>
                            <input type="text" class="input input-long" name="title" value="{{$mailer->title}}">
                            <div data-input="title" class="form-error"></div>
                            <div class="form-note">@lang('ui.mailerTitleHelp')</div>

                            <label class="label">@lang('ui.mailerKeyword')</label>
                            <input type="text" class="input input-long" name="filters[search]" value="{{$mailer->getFilter('search')}}">
                            <div data-input="filters[search]" class="form-error"></div>
                            <div class="form-note">@lang('ui.mailerKeywordHelp')</div>

                            <label class="label">@lang('ui.tag')</label>
                            <div class="form-category">
                                <select class="styled" name="filters[category]">
                                    @foreach (\App\Models\Category::active()->get() as $c)
                                        <option value="{{$c->id}}" @selected($mailer->getFilter('category') == $c->id)>{{$c->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($mailer->getFilter('author'))
                                <label class="label">@lang('ui.author')</label>
                                <input type="text" class="input" name="author_name" value="{{$mailer->getFilter('author', true)->name}}" disabled>
                                <input type="hidden" class="input" name="filters[author]" value="{{$mailer->getFilter('author')}}">
                                <div class="form-note">@lang('ui.mailerAuthorHelp') <a href="" class="remove-author-btn">@lang('ui.removed')</a></div>
                            @endif
                        </div>
                        <div class="form-section">
                            <div class="add-radio">
                                <div class="add-radio-col-50">
                                    <label class="label">@lang('ui.cost')</label>
                                    <div class="select-block">
                                        <select class="styled" name="filters[currency]">
                                            <option value="">@lang('ui.notSpecified')</option>
                                            @foreach (currencies() as $key => $symbol)
                                                <option value="{{$key}}" @selected($mailer->getFilter('currency') == $key)>{{strtoupper($key)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="price-input">
                                        <input type="text" class="input format-cost" name="filters[cost_from]" placeholder="@lang('ui.from')" value="{{$mailer->getFilter('cost_from')}}">
                                        <span class="price-input-divider">-</span>
                                        <input type="text" class="input format-cost" name="filters[cost_to]" placeholder="@lang('ui.to')" value="{{$mailer->getFilter('cost_to')}}">
                                    </div>
                                    <div data-input="cost_from" class="form-error"></div>
                                    <div data-input="cost_to" class="form-error"></div>
                                </div>
                                <div class="add-radio-col-50">
                                    <label class="label">@lang('ui.region')</label>
                                    <div class="select-block">
                                        <select class="styled" name="filters[country]">
                                            <option value="0">@lang('ui.notSpecified')</option>
                                            @foreach (\App\Models\Post::countries() as $key => $name)
                                                <option value="{{$key}}" @selected($mailer->getFilter('country') == $key)>{{$name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="add-radio">
                                <div class="add-radio-col">
                                    <label class="label">@lang('ui.types')</label>
                                    <div class="check-block">
                                        @foreach (\App\Models\Post::TYPES as $item)
                                            <div class="check-item">
                                                <input type="checkbox" class="check-input" name="filters[types][]" id="{{$item}}" value="{{$item}}" @checked(in_array($item, $mailer->getFilter('types')??[]))>
                                                <label for="{{$item}}" class="check-label">{{\App\Models\Post::typeReadable($item)}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div data-input="type" class="form-error"></div>
                                </div>
                                <div class="add-radio-col">
                                    <label class="label">@lang('ui.conditions')</label>
                                    <div class="check-block">
                                        @foreach (\App\Models\Post::CONDITIONS as $item)
                                            <div class="check-item">
                                                <input type="checkbox" class="check-input" name="filters[conditions][]" value="{{$item}}" id="{{$item}}" {{in_array($item, $mailer->getFilter('conditions')??[]) ? 'checked' : ''}}>
                                                <label for="{{$item}}" class="check-label">{{\App\Models\Post::conditionReadable($item)}}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div data-input="condition" class="form-error"></div>
                                </div>
                                <div class="add-radio-col">
                                    <label class="label">@lang('ui.urgent')</label>
                                    <div class="check-block">
                                        <div class="check-item">
                                            <input type="checkbox" class="check-input" name="filters[is_urgent][]" id="is-urgent-1" value="1" @checked(in_array(1, $mailer->getFilter('is_urgent')??[]))>
                                            <label for="is-urgent-1" class="check-label">@lang('ui.yes')</label>
                                        </div>
                                        <div class="check-item">
                                            <input type="checkbox" class="check-input" name="filters[is_urgent][]" id="is-urgent-0" value="0" @checked(in_array(0, $mailer->getFilter('is_urgent')??[]))>
                                            <label for="is-urgent-0" class="check-label">@lang('ui.no')</label>
                                        </div>
                                    </div>
                                    <div data-input="type" class="form-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-button-block">
                            <button type="submit" class="button">@lang('ui.saveChanges')</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
