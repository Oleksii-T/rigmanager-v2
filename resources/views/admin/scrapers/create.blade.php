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
                            <label>Sleep <small>between requests</small></label>
                            <input name="sleep" type="number" class="form-control">
                            <span data-input="sleep" class="input-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category selector</label>
                            <input name="category_selector" type="text" class="form-control">
                            <span data-input="category_selector" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pagination selector</label>
                            <input name="pagination_selector" type="text" class="form-control">
                            <span data-input="pagination_selector" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Post selector</label>
                            <input name="post_selector" type="text" class="form-control">
                            <span data-input="post_selector" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Post link selector</label>
                            <input name="post_link_selector" type="text" class="form-control">
                            <span data-input="post_link_selector" class="input-error"></span>
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
                <div class="row og-selector">
                    <div class="col-md-2">
                        <div class="form-group">
                            <input name="selectors[0][name]" type="text" class="form-control" value="title">
                            <span data-input="selectors.0.name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input name="selectors[0][value]" type="text" class="form-control">
                            <span data-input="selectors.0.value" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input name="selectors[0][attribute]" type="text" class="form-control">
                            <span data-input="selectors.0.attribute" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <div class="form-check">
                                <input id="is_debug" name="selectors[0][is_multiple]" class="form-check-input" type="checkbox" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <div class="form-check">
                                <input id="is_debug" name="selectors[0][from_posts_page]" class="form-check-input" type="checkbox" value="1">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <div class="form-check">
                                <input id="is_debug" name="selectors[0][required]" class="form-check-input" type="checkbox" value="1" checked>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 remove-wraper d-none">
                        <div class="form-group">
                            <button type="button" class="btn btn-danger remove-selector">x</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.scrapers.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/scrapers.js')}}"></script>
@endpush
