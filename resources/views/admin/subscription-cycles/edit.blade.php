@extends('layouts.admin.app')

@section('title', 'Edit Subscription Cycle')

@section('content_header')
    <x-admin.title
        text="Edit Subscription Cycle"
        bcRoute="admin.subscription-cycles.edit"
    />
@stop

@section('content')
    <form action="{{ route('admin.subscription-cycles.update', $subscriptionCycle) }}" method="POST" class="general-ajax-submit">
        @csrf
        @method('PUT')
        <input name="is_active" type="hidden" class="form-control" value="0">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Invoice <small>(json)</small></label>
                            <input name="invoice" type="text" class="form-control" value="{{json_encode($subscriptionCycle->invoice)}}">
                            <span data-input="invoice" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Price</label>
                            <input name="price" type="text" class="form-control" value="{{$subscriptionCycle->price}}">
                            <span data-input="price" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Expire At <small>subs without stripe will not auto-extend</small></label>
                            <input name="expire_at" type="text" class="form-control daterangepicker-single" value="{{$subscriptionCycle->expire_at}}">
                            <span data-input="expire_at" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Is active</label>
                            <input name="is_active" type="checkbox" class="form-control" value="1" @checked($subscriptionCycle->is_active)>
                            <span data-input="is_active" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.subscriptions.show', $subscriptionCycle->subscription) }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
