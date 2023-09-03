@extends('layouts.admin.app')

@section('title', 'Blog Create')

@section('content_header')
    <x-admin.title
        text="Blog create"
    />
@stop

@section('content')
    <form action="{{route('admin.blogs.store')}}" method="POST" class="pb-3 general-ajax-submit">
        @csrf
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="mr-2">General Info</span>
                </h3>
            </div>
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
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="select-block">
                                <select class="form-control" name="status" style="width: 100%">
                                    @foreach (\App\Enums\BlogStatus::all() as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span data-input="status" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Country</label>
                            <div class="select-block">
                                <select class="form-control select2" name="country" style="width: 100%">
                                    <option value="">Select country</option>
                                    @foreach (countries() as $key => $value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span data-input="country" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tags</label>
                            <div class="select-block">
                                <select class="form-control select2-tags" name="tags[]" style="width: 100%" multiple>
                                    @foreach ($tags as $tag)
                                        <option value="{{$tag}}">{{$tag}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span data-input="country" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Sub Title</label>
                            <x-admin.multi-lang-input name="sub_title" textarea="1" />
                            <span data-input="sub_title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Body</label>
                            <x-admin.multi-lang-input name="body" richtext="1" />
                            <span data-input="body" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="mr-2">Meta</span>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title</label>
                            <x-admin.multi-lang-input name="meta_title" />
                            <span data-input="meta_title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description</label>
                            <x-admin.multi-lang-input name="meta_description" />
                            <span data-input="meta_description" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="mr-2">Images</span>
                    <button type="button" class="btn btn-success add-file-input auto-add">Add</button>
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
                    <span data-input="images" class="input-error"></span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary text-dark">Cancel</a>
    </form>
@endsection
