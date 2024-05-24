@extends('layouts.admin.app')

@section('title', 'Create Scraper')

@section('content_header')
    <x-admin.title
        text="Create Scraper"
    />
@stop

@section('content')
    <form action="{{ route('admin.scrapers.store') }}" method="POST" class="general-ajax-submit">
        @csrf
        <input type="hidden" name="with_cache" value="0">
        <input type="hidden" name="is_debug" value="0">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">General</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control">
                            <span data-input="name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control select2" name="user_id">
                                <option value="">None</option>
                                @foreach (\App\Models\User::all() as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="user_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Base Urls <small>comma separated</small></label>
                            <input name="base_urls" type="text" class="form-control">
                            <span data-input="base_urls" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Scrape limit</label>
                            <input name="scrape_limit" type="number" class="form-control">
                            <span data-input="scrape_limit" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sleep <small>between requests</small></label>
                            <input name="sleep" type="number" class="form-control">
                            <span data-input="sleep" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>-</label>
                            <div class="form-check">
                                <input id="with_cache" name="with_cache" class="form-check-input" type="checkbox" value="1">
                                <label for="with_cache" class="form-check-label">Cache enabled</label>
                            </div>
                            <div class="form-check">
                                <input id="is_debug" name="is_debug" class="form-check-input" type="checkbox" value="1">
                                <label for="is_debug" class="form-check-label">Debug enabled</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Selectors</h3>
                <div class="card-tools">
                    <div class="input-group">
                        <a href="#" class="btn btn-success add-selector">
                            +
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body selectors-wraper">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Selector Name</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Value</label>
                        </div>
                    </div>
                </div>
                @foreach (\App\Models\Scraper::getDefSelectors() as $selector => $value)
                    <div class="row og-selector">
                        <div class="col-md-5">
                            <div class="form-group">
                                <input name="selectors[name][]" type="text" class="form-control" value="{{$selector}}">
                                <span data-input="selectors.name" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input name="selectors[value][]" type="text" class="form-control" value="{{$value}}">
                                <span data-input="selectors.value" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-1 remove-wraper d-none">
                            <div class="form-group">
                                <button type="button" class="btn btn-danger remove-selector">x</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.scrapers.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/scrapers.js')}}"></script>
@endpush
