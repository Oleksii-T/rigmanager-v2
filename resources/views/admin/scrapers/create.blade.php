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
                            <label>Exclude Urls <small>comma separated</small></label>
                            <input name="exclude_urls" type="text" class="form-control">
                            <span data-input="exclude_urls" class="input-error"></span>
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Selector Name</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Value</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Attribute</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Is Multiple</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>From posts page</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Required</label>
                        </div>
                    </div>
                </div>
                @foreach (\App\Models\Scraper::getDefSelectors() as $i => $name)
                    <div class="row og-selector">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input name="selectors[{{$i}}][name]" type="text" class="form-control" value="{{$name}}">
                                <span data-input="selectors.{{$i}}.name" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input name="selectors[{{$i}}][value]" type="text" class="form-control">
                                <span data-input="selectors.{{$i}}.value" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input name="selectors[{{$i}}][attribute]" type="text" class="form-control">
                                <span data-input="selectors.{{$i}}.attribute" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="form-check">
                                    <input id="is_debug" name="selectors[{{$i}}][is_multiple]" class="form-check-input" type="checkbox" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="form-check">
                                    <input id="is_debug" name="selectors[{{$i}}][from_posts_page]" class="form-check-input" type="checkbox" value="1">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="form-check">
                                    <input id="is_debug" name="selectors[{{$i}}][required]" class="form-check-input" type="checkbox" value="1" checked>
                                </div>
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
