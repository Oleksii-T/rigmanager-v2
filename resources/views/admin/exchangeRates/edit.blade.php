@extends('layouts.admin.app')

@section('title', 'Edt Rate')

@section('content_header')
    <x-admin.title
        text="Edit Rate"
    />
@stop

@section('content')
    <form action="{{ route('admin.exchange-rates.update', $exchangeRate) }}" method="POST" class="general-ajax-submit">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>From</label>
                            <input type="text" class="form-control" value="{{$exchangeRate->from}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>To</label>
                            <input type="text" class="form-control" value="{{$exchangeRate->to}}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Cost</label>
                            <input type="text" name="cost" class="form-control" value="{{$exchangeRate->cost}}">
                            <span data-input="cost" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Auto Update</label>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="auto_update" name="auto_update" value="1" @checked($exchangeRate->auto_update)>
                                <label for="auto_update" class="custom-control-label">Yes</label>
                            </div>
                            <span data-input="auto_update" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.exchange-rates.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
