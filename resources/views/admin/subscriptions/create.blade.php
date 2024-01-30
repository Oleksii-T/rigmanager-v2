@extends('layouts.admin.app')

@section('title', 'Create Subscription')

@section('content_header')
    <x-admin.title
        text="Create Subscription"
        bcRoute="admin.subscriptions.create"
    />
@stop

@section('content')
    <form action="{{ route('admin.subscriptions.store') }}" method="POST" class="general-ajax-submit">
        @csrf
        <input name="is_active" type="hidden" class="form-control" value="0">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Plan</label>
                            <select name="subscription_plan_id" class="form-control">
                                @foreach ($plans as $plan)
                                    <option value="{{$plan->id}}">{{$plan->id}}: {{$plan->title}} {{$plan->level}} ({{$plan->interval}})</option>
                                @endforeach
                            </select>
                            <span data-input="subscription_plan_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User</label>
                            <select name="user_id" class="form-control">
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->id}}: {{$user->name}} {{'<' . $user->email . '>'}}</option>
                                @endforeach
                            </select>
                            <span data-input="user_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="active">Active</option>
                                <option value="canceled">Canceled</option>
                                <option value="incomplete">Incomplete</option>
                            </select>
                            <span data-input="status" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Provider</label>
                            <select name="provider" class="form-control">
                                <option value="stripe">Stripe</option>
                                <option value="manual">Manual</option>
                            </select>
                            <span data-input="provider" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>External Id</label>
                            <input name="external_id" type="text" class="form-control">
                            <span data-input="external_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Maximum cycles <small>(for 'manual' sub only)</small></label>
                            <input name="max_cycles" type="number" class="form-control">
                            <span data-input="max_cycles" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>

    <x-admin.trivias />
@endsection
