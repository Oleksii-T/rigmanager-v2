<ul class="nav nav-tabs" role="tablist" style="display: flex;">
    @foreach(LaravelLocalization::getLocalesOrder() as $localeCode => $properties)
        <li role="presentation" class="{{$loop->first ? 'active' : ''}}" class="nav-item">
            <a
                href="#{{Str::slug($name)}}-{{$localeCode}}"
                data-locale="{{$localeCode}}"
                role="tab"
                data-toggle="tab"
                class="nav-link {{$loop->first ? 'active' : ''}}"
            >
                {{$localeCode}}
            </a>
        </li>
    @endforeach
</ul>
<div class="tab-content">
    @foreach(LaravelLocalization::getLocalesOrder() as $localeCode => $properties)
        <div role="tabpanel" class="tab-pane {{$loop->first ? 'active' : ''}}" id="{{Str::slug($name)}}-{{$localeCode}}">
            @php
                $name_ = ($prefix??'') . $name . '[' . $localeCode . ']';
                $val = old($name.'['.$localeCode.']', null);
                $val ??= isset($model) ? $model->translated($name, $localeCode) : null;
                $val ??= isset($value) ? ($value[$localeCode]??'') : '';
            @endphp
            @if (($textarea??false) || ($richtext??false) || ($richtextPostsDesc??false))
                <textarea class="form-control {{$richtext??false ? 'summernote' : ''}} {{$richtextPostsDesc??false ? 'posts-rich-desc' : ''}}" name="{{$name_}}" rows="4">{{$val}}</textarea>
            @else
                <input 
                    class="form-control {{$count??false ? 'count-input-chart' : ''}}" 
                    data-locale="{{$localeCode}}" 
                    name="{{$name_}}" 
                    type="text" 
                    value="{{$val}}"
                >
            @endif
        </div>
    @endforeach
</div>
