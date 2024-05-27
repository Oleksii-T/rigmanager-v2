<div class="row">
    <div class="col-md-4 col-lg-3 col-xl-2">
        <div class="form-group">
            <label>Cost</label>
            <input type="text" class="form-control" name="cost" value="{{$post?->cost}}">
            <span data-input="cost" class="input-error"></span>
        </div>
    </div>
    <div class="col-md-4 col-lg-3 col-xl-2">
        <div class="form-group">
            <label>Cost From</label>
            <input type="text" class="form-control" name="cost_from" value="{{$post?->cost_from}}">
            <span data-input="cost_from" class="input-error"></span>
        </div>
    </div>
    <div class="col-md-4 col-lg-3 col-xl-2">
        <div class="form-group">
            <label>Cost To</label>
            <input type="text" class="form-control" name="cost_to" value="{{$post?->cost_to}}">
            <span data-input="cost_to" class="input-error"></span>
        </div>
    </div>
    <div class="col-md-4 col-lg-3 col-xl-2">
        <div class="form-group">
            <label>Cost Per</label>
            <input type="text" class="form-control" name="cost_per" value="{{$post?->cost_per}}">
            <span data-input="cost_per" class="input-error"></span>
        </div>
    </div>
    <div class="col-md-4 col-lg-3 col-xl-2">
        <div class="form-group">
            <label>Currency</label>
            <select class="form-control" name="currency">
                @foreach (currencies() as $key => $symbol)
                    <option value="{{$key}}" @selected($post?->currency)>{{strtoupper($key)}}</option>
                @endforeach
            </select>
            <span data-input="currency" class="input-error"></span>
        </div>
    </div>
    <div class="col-md-2 col-lg-2 col-xl-1">
        <div class="form-group">
            <label>Cost Double Value</label>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="is_double_cost" name="is_double_cost" value="1" @checked($post?->is_double_cost)>
                <label for="is_double_cost" class="custom-control-label">Yes</label>
            </div>
            <span data-input="is_double_cost" class="input-error"></span>
        </div>
    </div>
    <div class="col-md-2 col-lg-2 col-xl-1">
        <div class="form-group">
            <label>Price Requests</label>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" type="checkbox" id="is_tba" name="is_tba" value="1" @checked($post?->is_tba)>
                <label for="is_tba" class="custom-control-label">Yes</label>
            </div>
            <span data-input="is_tba" class="input-error"></span>
        </div>
    </div>
</div>