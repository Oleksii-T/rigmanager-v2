@extends('layouts.admin.app')

@section('title', 'Create Plan')

@section('content_header')
    <x-admin.title
        text="Create Plan"
        bcRoute="admin.subscription-plans.create"
    />
@stop

@section('content')
    <form action="{{ route('admin.subscription-plans.store') }}" method="POST" class="general-ajax-submit">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title</label>
                            <x-admin.multi-lang-input name="title" />
                            <span data-input="title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <x-admin.multi-lang-input name="slug" />
                            <span data-input="slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price ({{\App\Models\Setting::get('currency_sign')}})</label>
                            <input name="price" type="text" class="form-control">
                            <span data-input="price" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Trial days</label>
                            <input name="trial" type="number" class="form-control" value="0">
                            <span data-input="trial" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Interval</label>
                            <select class="form-control" name="interval">
                                @foreach (\App\Models\SubscriptionPlan::INTERVALS as $inteval)
                                    <option value="{{$inteval}}">{{ucfirst($inteval)}}</option>
                                @endforeach
                            </select>
                            <span data-input="role" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description</label>
                            <x-admin.multi-lang-input name="description" textarea="1" />
                            <span data-input="description" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.subscription-plans.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
