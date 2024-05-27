@php
    $class = isset($wide) ? 'col-3' : 'col-md-4 col-lg-3 col-xl-2';
@endphp
<div class="{{$class}}">
    <div class="form-group">
        <label>Quantity</label>
        <input type="text" class="form-control" name="amount" value="{{$post?->amount}}">
        <span data-input="amount" class="input-error"></span>
    </div>
</div>
@if (!isset($withoutCountry))
    <div class="{{$class}}">
        <div class="form-group">
            <label>Country</label>
            <select class="form-control" name="country">
                @foreach (countries() as $key => $name)
                    <option value="{{$key}}" @selected($post?->country == $key)>{{$name}}</option>
                @endforeach
            </select>
            <span data-input="country" class="input-error"></span>
        </div>
    </div>
@endif
<div class="{{$class}}">
    <div class="form-group">
        <label>Manufacturer</label>
        <input type="text" class="form-control" name="manufacturer" value="{{$post?->manufacturer}}">
        <span data-input="manufacturer" class="input-error"></span>
    </div>
</div>
<div class="{{$class}}">
    <div class="form-group">
        <label>Manufacture Date</label>
        <input type="text" class="form-control" name="manufacture_date" value="{{$post?->manufacture_date}}">
        <span data-input="manufacture_date" class="input-error"></span>
    </div>
</div>
<div class="{{$class}}">
    <div class="form-group">
        <label>Part Number</label>
        <input type="text" class="form-control" name="part_number" value="{{$post?->part_number}}">
        <span data-input="part_number" class="input-error"></span>
    </div>
</div>