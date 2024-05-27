<div class="col-12 categories-level-selects row">
    <input type="hidden" name="category_id" value="{{$post?->category_id}}">
    <div class="col-4 cat-lev-x cat-lev-1">
        <div class="form-group select-block">
            <select class="form-control">
                <option value="">@lang('ui.chooseTag')</option>
                @foreach ($categsFirstLevel as $c)
                    <option value="{{$c->id}}" data-suggestions="{{$c->suggestions}}" @selected($post && $activeLevels[0] == $c->id)>{{$c->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-4 cat-lev-x cat-lev-2">
        @foreach ($categsSecondLevel as $parentId => $secondLevel)
            <div class="form-group select-block {{$post && $activeLevels[0] == $parentId ? '' : 'd-none'}}" data-parentcateg="{{$parentId}}">
                <select class="form-control">
                    <option value="">@lang('ui.chooseNextTag')</option>
                    @foreach ($secondLevel as $c)
                        <option value="{{$c->id}}" data-suggestions="{{$c->suggestions}}" @selected($post && ($activeLevels[1]??null) == $c->id)>{{$c->name}}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>
    <div class="col-4 cat-lev-x cat-lev-3">
        @foreach ($categsThirdLevel as $parentId => $thirdLevel)
            <div class="form-group select-block {{$post && ($activeLevels[1]??null) == $parentId ? '' : 'd-none'}}" data-parentcateg="{{$parentId}}">
                <select class="form-control">
                    <option value="">@lang('ui.chooseNextTag')</option>
                    @foreach ($thirdLevel as $c)
                        <option value="{{$c->id}}" data-suggestions="{{$c->suggestions}}" @selected($post && ($activeLevels[2]??null) == $c->id)>{{$c->name}}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div> 
</div>