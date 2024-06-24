@extends('layouts.admin.app')

@section('title', 'Create Scraper')

@section('content_header')
    <x-admin.title
        text="Edit Scraper #{{$scraper->id}}"
    />
@stop

@section('content')
    <form action="{{ route('admin.scrapers.update', $scraper) }}" method="POST" class="general-ajax-submit">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">General</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input name="name" type="text" class="form-control" value="{{$scraper->name}}">
                            <span data-input="name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control select2" name="user_id">
                                <option value="">None</option>
                                @foreach (\App\Models\User::all() as $user)
                                    <option value="{{$user->id}}" @selected($user->id == $scraper->user_id)>{{$user->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="user_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Base Urls <small>comma separated</small></label>
                            <input name="base_urls" type="text" class="form-control" value="{{implode(', ', $scraper->base_urls)}}">
                            <span data-input="base_urls" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Exclude Urls <small>comma separated</small></label>
                            <input name="exclude_urls" type="text" class="form-control" value="{{implode(', ', $scraper->exclude_urls??[])}}">
                            <span data-input="exclude_urls" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Sleep <small>between requests</small></label>
                            <input name="sleep" type="number" class="form-control" value="{{$scraper->sleep}}">
                            <span data-input="sleep" class="input-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Category selector</label>
                            <input name="category_selector" type="text" class="form-control" value="{{$scraper->category_selector}}">
                            <span data-input="category_selector" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pagination selector</label>
                            <input name="pagination_selector" type="text" class="form-control" value="{{$scraper->pagination_selector}}">
                            <span data-input="pagination_selector" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Post selector</label>
                            <input name="post_selector" type="text" class="form-control" value="{{$scraper->post_selector}}">
                            <span data-input="post_selector" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Post link selector</label>
                            <input name="post_link_selector" type="text" class="form-control" value="{{$scraper->post_link_selector}}">
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
                @foreach ($scraper->selectors as $i => $selector)
                    <div class="row og-selector">
                        <div class="col-md-2">
                            <div class="form-group">
                                <input name="selectors[{{$i}}][name]" type="text" class="form-control" value="{{$selector['name']}}">
                                <span data-input="selectors.name" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input name="selectors[{{$i}}][value]" type="text" class="form-control" value="{{$selector['value']}}">
                                <span data-input="selectors.value" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input name="selectors[{{$i}}][attribute]" type="text" class="form-control" value="{{$selector['attribute']}}">
                                <span data-input="selectors.attribute" class="input-error"></span>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="hidden" name="selectors[{{$i}}][is_multiple]" value="0">
                                    <input name="selectors[{{$i}}][is_multiple]" class="form-check-input" type="checkbox" value="1" @checked($selector['is_multiple']??false)>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="hidden" name="selectors[{{$i}}][from_posts_page]" value="0">
                                    <input name="selectors[{{$i}}][from_posts_page]" class="form-check-input" type="checkbox" value="1" @checked($selector['from_posts_page']??false)>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="hidden" name="selectors[{{$i}}][required]" value="0">
                                    <input name="selectors[{{$i}}][required]" class="form-check-input" type="checkbox" value="1" @checked($selector['required']??false)>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 remove-wraper">
                            <div class="form-group">
                                <button type="button" class="btn btn-danger remove-selector">x</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.scrapers.show', $scraper) }}" class="btn btn-outline-secondary text-dark min-w-100">Go to View</a>
        <a href="{{ route('admin.scrapers.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Back to scrapers</a>
    </form>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/scrapers.js')}}"></script>
@endpush
