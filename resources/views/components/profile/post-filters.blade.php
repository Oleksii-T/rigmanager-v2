{{-- search --}}
<div class="col-6">
    <label class="label">@lang('ui.search')</label>
    <form method="GET" action="{{route('profile.posts')}}">
        <fieldset>
            <div class="tt-input-field-wrapper">
                <input type="text" class="input typeahead-input" data-ttt="{{$ttt}}" placeholder="@lang('ui.search')" name="search">
            </div>
            <button class="search-button"></button>
        </fieldset>
    </form>
</div>

<!--currency-->
<div class="col-6">
    <label class="label">@lang('ui.currency')</label>
    <div class="select-block">
        <select class="styled" name="currency">
            <option value="">{{__('ui.notSpecified')}}</option>
            @foreach (currencies() as $key => $symbol)
                <option value="{{$key}}" @selected(request()->currency == $key)>{{strtoupper($key)}}</option>
            @endforeach
        </select>
    </div>
</div>

<!--cost-->
<div class="col-6">
    <label class="label">@lang('ui.cost')</label>
    <div class="price-input">
        <input type="text" class="input" name="cost_from" placeholder="@lang('ui.from')" value="{{request()->cost_from}}">
        <span class="price-input-divider">-</span>
        <input type="text" class="input" name="cost_to" placeholder="@lang('ui.to')" value="{{request()->cost_to}}">
    </div>
</div>

<!--country-->
<div class="col-6">
    <label class="label">@lang('ui.country')</label>
    <div class="select-block">
        <select class="styled" name="country">
            <option value="0">{{__('ui.notSpecified')}}</option>
            @foreach (\App\Models\Post::countries() as $key => $name)
                <option value="{{$key}}">{{$name}}</option>
            @endforeach
        </select>
    </div>
</div>

<!--condition-->
<div class="col-4">
    <label class="label">@lang('ui.condition')</label>
    <div id="condition" class="check-block">
        @foreach (\App\Models\Post::CONDITIONS as $item)
            <div class="check-item">
                <input type="checkbox" class="check-input" name="conditions" value="{{$item}}" id="{{$item}}" @checked(in_array($item, request()->conditions??[]))>
                <label for="{{$item}}" class="check-label">{{\App\Models\Post::conditionReadable($item)}}</label>
            </div>
        @endforeach
    </div>
</div>

<!--type-->
<div class="col-4">
    <label class="label">@lang('ui.postType')</label>
    <div id="type" class="check-block">
        @foreach (\App\Models\Post::TYPES as $item)
            <div class="check-item">
                <input type="checkbox" class="check-input" name="types" id="{{$item}}" value="{{$item}}" @checked(in_array($item, request()->types??[]))>
                <label for="{{$item}}" class="check-label">{{\App\Models\Post::typeReadable($item)}}</label>
            </div>
        @endforeach
    </div>
</div>

<!--urgent-->
<div class="col-4">
    <label class="label">@lang('ui.urgent')</label>
    <div id="urgent" class="check-block">
        <div class="check-item">
            <input type="checkbox" class="check-input" name="is_urgent" id="is-urgent-1" value="1" @checked(in_array(1, request()->is_urgent??[]))>
            <label for="is-urgent-1" class="check-label">@lang('ui.yes')</label>
        </div>
        <div class="check-item">
            <input type="checkbox" class="check-input"  name="is_urgent" id="is-urgent-0" value="0" @checked(in_array(0, request()->is_urgent??[]))>
            <label for="is-urgent-0" class="check-label">@lang('ui.no')</label>
        </div>
    </div>
</div>

<!--sorting-->
<div class="col-6">
    <label class="label">@lang('ui.sort')</label>
    <div class="select-block">
        <select class="styled" name="sorting">
            @foreach (\App\models\Post::getSorts() as $key => $name)
                <option value="{{$key}}">{{$name}}</option>
            @endforeach
        </select>
    </div>
</div>
