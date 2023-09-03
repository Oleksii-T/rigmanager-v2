@extends('layouts.admin.app')

@section('title', 'Blog Edit')

@section('content_header')
    <x-admin.title
        text="Blog Edit #{{$blog->id}}"
    />
@stop

@section('content')
    <form action="{{route('admin.blogs.update', $blog)}}" method="POST" class="pb-3 general-ajax-submit">
        @csrf
        @method('PUT')
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
                            <x-admin.multi-lang-input name="title" :model="$blog"/>
                            <span data-input="title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <x-admin.multi-lang-input name="slug" :model="$blog"/>
                            <span data-input="slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="select-block">
                                <select class="form-control" name="status" style="width: 100%">
                                    @foreach (\App\Enums\BlogStatus::all() as $key => $value)
                                        <option value="{{$key}}" @selected($blog->status->value == $key)>{{$value}}</option>
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
                                        <option value="{{$key}}" @selected($blog->country == $key)>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span data-input="country" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Source Name</label>
                            <input type="text" class="form-control" name="source_name" value="{{$blog->source_name}}">
                            <span data-input="source_name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Source Link</label>
                            <input type="text" class="form-control" name="source_link" value="{{$blog->source_link}}">
                            <span data-input="source_link" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tags</label>
                            <div class="select-block">
                                <select class="form-control select2-tags" name="tags[]" style="width: 100%" multiple>
                                    @foreach ($tags as $tag)
                                        <option value="{{$tag}}" @selected(in_array($tag, $blog->tags??[]))>{{$tag}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span data-input="country" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Posted At</label>
                            <input type="text" name="posted_at" class="form-control daterangepicker-single">
                            <span data-input="posted_at" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Sub Title</label>
                            <x-admin.multi-lang-input name="sub_title" textarea="1" :model="$blog" />
                            <span data-input="sub_title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Body</label>
                            <x-admin.multi-lang-input name="body" richtext="1" :model="$blog" />
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
                            <x-admin.multi-lang-input name="meta_title" :model="$blog" />
                            <span data-input="meta_title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Description</label>
                            <x-admin.multi-lang-input name="meta_description" :model="$blog" />
                            <span data-input="meta_description" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">
                    <span class="mr-2">Thumbnail</span>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 file-input">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="thumbnail">
                                    <label class="custom-file-label">{{$blog->thumbnail->original_name}}</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text delete-file-input bg-warning">Remove</span>
                                </div>
                            </div>
                            <img src="{{$blog->thumbnail->url}}" alt="" class="custom-file-preview">
                        </div>
                    </div>
                    <span data-input="thumbnail" class="input-error"></span>
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
                    @foreach ($blog->images as $image)
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
                    @foreach ($blog->documents as $document)
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
        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary text-dark">Cancel</a>
    </form>
@endsection
