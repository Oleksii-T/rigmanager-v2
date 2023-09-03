@extends('layouts.admin.app')

@section('title', 'Edit Post')

@section('content_header')
    <x-admin.title
        text="Edit Post"
    />
@stop

@section('content')
    <form action="{{route('admin.posts.update', $post)}}" method="POST" class="pb-3 general-ajax-submit">
        @csrf
        @method('PUT')
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">General info</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title</label>
                            <x-admin.multi-lang-input name="title" :model="$post" />
                            <span data-input="title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <x-admin.multi-lang-input name="slug" :model="$post" />
                            <span data-input="slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description</label>
                            <x-admin.multi-lang-input name="description" :model="$post" textarea="1" />
                            <span data-input="description" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control select2" name="user_id">
                                <option value="">Select User</option>
                                @foreach (\App\Models\User::all() as $model)
                                    <option value="{{$model->id}}" @selected($post->user_id == $model->id)>{{$model->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="user_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control select2" name="category_id">
                                <span>Test head line</span>
                                @foreach (\App\Models\Category::active()->get() as $c)
                                    <option value="{{$c->id}}" @selected($post->category_id == $c->id)>{{$c->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="category_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control" name="status">
                                @foreach (\App\Models\Post::STATUSES as $item)
                                    <option value="{{$item}}" @selected($post->status == $item)>{{readable($item)}}</option>
                                @endforeach
                            </select>
                            <span data-input="status" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" name="type">
                                @foreach (\App\Models\Post::TYPES as $item)
                                    <option value="{{$item}}" @selected($post->type == $item)>{{readable($item)}}</option>
                                @endforeach
                            </select>
                            <span data-input="type" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Condition</label>
                            <select class="form-control" name="condition">
                                @foreach (\App\Models\Post::CONDITIONS as $item)
                                    <option value="{{$item}}" @selected($post->condition == $item)>{{readable($item)}}</option>
                                @endforeach
                            </select>
                            <span data-input="condition" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Original Lang</label>
                            <select class="form-control" name="origin_lang">
                                @foreach(LaravelLocalization::getLocalesOrder() as $localeCode => $properties)
                                    <option value="{{$localeCode}}" @selected($post->origin_lang == $localeCode)>{{readable($localeCode)}}</option>
                                @endforeach
                            </select>
                            <span data-input="origin_lang" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Duration</label>
                            <select class="form-control" name="duration">
                                @foreach (\App\Models\Post::DURATIONS as $item)
                                    <option value="{{$item}}" @selected($post->duration == $item)>{{readable($item)}}</option>
                                @endforeach
                            </select>
                            <span data-input="duration" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xl-1">
                        <div class="form-group">
                            <label>Is Active</label>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_active" name="is_active" value="1" @checked($post->is_active)>
                                <label for="is_active" class="custom-control-label">Yes</label>
                            </div>
                            <span data-input="is_active" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xl-1">
                        <div class="form-group">
                            <label>Is Urgent</label>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_urgent" name="is_urgent" value="1" @checked($post->is_urgent)>
                                <label for="is_urgent" class="custom-control-label">Yes</label>
                            </div>
                            <span data-input="is_urgent" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Additional Info</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="text" class="form-control" name="amount" value="{{$post->amount}}">
                            <span data-input="amount" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Country</label>
                            <select class="form-control" name="country">
                                @foreach (countries() as $key => $name)
                                    <option value="{{$key}}" @selected($post->country == $key)>{{$name}}</option>
                                @endforeach
                            </select>
                            <span data-input="country" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Manufacturer</label>
                            <input type="text" class="form-control" name="manufacturer" value="{{$post->manufacturer}}">
                            <span data-input="manufacturer" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Manufacture Date</label>
                            <input type="text" class="form-control" name="manufacture_date" value="{{$post->manufacture_date}}">
                            <span data-input="manufacture_date" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Part Number</label>
                            <input type="text" class="form-control" name="part_number" value="{{$post->part_number}}">
                            <span data-input="part_number" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Cost</label>
                            <input type="text" class="form-control" name="cost" value="{{$post->cost}}">
                            <span data-input="cost" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Currency</label>
                            <select class="form-control" name="currency">
                                @foreach (currencies() as $key => $symbol)
                                    <option value="{{$key}}" @selected($post->currency)>{{strtoupper($key)}}</option>
                                @endforeach
                            </select>
                            <span data-input="currency" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="mr-2">Images</span>
                    <button type="button" class="btn btn-success add-file-input">Add</button>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 file-input d-none clone">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="images[]">
                                    <label class="custom-file-label">Choose image</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text delete-file-input bg-warning">Remove</span>
                                </div>
                            </div>
                            <img src="" alt="" class="custom-file-preview">
                        </div>
                    </div>
                    @foreach ($post->images as $image)
                        <div class="col-md-4 file-input">
                            <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="images[]">
                                        <label class="custom-file-label">{{$image->original_name}}</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text delete-file-input bg-warning">Remove</span>
                                    </div>
                                </div>
                                <img src="{{$image->url}}" alt="" class="custom-file-preview">
                            </div>
                        </div>
                    @endforeach
                    <span data-input="images" class="input-error"></span>
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="mr-2">Documents</span>
                    <button type="button" class="btn btn-success add-file-input">Add</button>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 file-input d-none clone">
                        <div class="form-group show-uploaded-file-name">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="documents[]">
                                    <label class="custom-file-label">Choose document</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text delete-file-input bg-warning">Remove</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach ($post->documents as $document)
                        <div class="col-md-4 file-input">
                            <div class="form-group show-uploaded-file-name">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="images[]">
                                        <label class="custom-file-label">{{$document->original_name}}</label>
                                    </div>
                                    <div class="input-group-append">
                                        <a href="{{$document->url}}" class="input-group-text bg-info" download="{{$document->original_name}}">Download</a>
                                        <span class="input-group-text delete-file-input bg-warning">Remove</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <span data-input="documents" class="input-error"></span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary text-dark">Cancel</a>
    </form>
@endsection
