@extends('layouts.admin.app')

@section('title', 'Edit Post')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="float-left">
                    <h1 class="m-0">Edit Post #{{$post->id}}</h1>
                </div>
                <div class="float-left pl-3">
                    <a href="{{route('posts.show', $post)}}" class="btn btn-primary" target="_blank">
                        Preview
                    </a>
                    <a href="{{route('admin.activity-logs.index')}}?subject_type=App\Models\Post&subject_id={{$post->id}}" class="btn btn-primary" target="_blank">
                        Actions
                    </a>
                    <a href="{{route('admin.posts.views', $post)}}" class="btn btn-primary">
                        Views
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <form action="{{route('admin.posts.update', $post)}}" method="POST" class="pb-3 approve-form">
        @csrf
        @method('PUT')
        @if ($approvingPosts)
            <input type="hidden" name="approveFilters" value="{{request()->approveFilters}}">
        @endif
        <input type="hidden" name="deleted_images">
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">General info</h3>
                @if ($post->scraped_url)
                    <a style="padding-left: 10px; color:rgb(211, 211, 211)" href="{{$post->scraped_url}}" target="_blank">
                        {{$post->scraped_url}}
                    </a>
                @endif
                @if ($approvingPosts)
                    <div style="display:inline-block;float:right">
                        @if ($prev)
                            <a href="{{route('admin.posts.edit', $prev) . '?approveFilters=' . request()->approveFilters}}" class="{{$prev->getApprovingClass()}}">{{$prev->id}}</a>
                        @else
                            <a href="#">-</a>
                        @endif
                        >
                        {{$post->id}}
                        >
                        @if ($next)
                            <a href="{{route('admin.posts.edit', $next) . '?approveFilters=' . request()->approveFilters}}" class="{{$next->getApprovingClass()}}">{{$next->id}}</a>
                        @else
                            <a href="#">-</a>
                        @endif
                    </div>
                @endif
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
                            <x-admin.multi-lang-input name="slug" :model="$post" count="1"/>
                            <span data-input="slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Meta Title</label>
                            <x-admin.multi-lang-input name="meta_title" :model="$post" count="1" />
                            <span data-input="meta_title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Meta Description</label>
                            <x-admin.multi-lang-input name="meta_description" :model="$post" count="1" />
                            <span data-input="meta_description" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Description</label>
                            <x-admin.multi-lang-input name="description" :model="$post" richtextPostsDesc="1" />
                            <span data-input="description" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>Group</label>
                            <select class="form-control select2" name="category_id">
                                @foreach (\App\Enums\PostGroup::all() as $key => $name)
                                    <option value="{{$key}}" @selected($post->group->value == $key)>{{$name}}</option>
                                @endforeach
                            </select>
                            <span data-input="category_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3 col-xl-2">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control select2" name="user_id">
                                <option value="">Select User</option>
                                @foreach ($users as $model)
                                    <option value="{{$model->id}}" @selected($post->user_id == $model->id)>{{$model->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="user_id" class="input-error"></span>
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
                                @foreach (\App\Enums\PostType::all() as $key => $name)
                                    <option value="{{$key}}" @selected($post->type->value == $key)>{{$name}}</option>
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
                    @include('admin.posts.category-input')
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
                <h3 class="card-title">Price</h3>
            </div>
            <div class="card-body">
                @include('admin.posts.cost-input')
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Additional Info</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    @include('admin.posts.additional-inputs')
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
                        <div class="col-md-4 file-input" data-id="{{$image->id}}" data-input="deleted_images">
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
        @if ($approvingPosts)
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <span class="mr-2">Approving Progress ({{$approvingPosts->where('status', 'approved')->count()}}/{{$approvingPosts->count()}})</span>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($approvingPosts as $approvingPost)
                            @php
                                $cl = $approvingPost->getApprovingClass();
                                if ($post->id == $approvingPost->id) {
                                    $cl .= ' font-weight-bold font-italic';
                                }
                            @endphp
                            <div class="col-md-4">
                                <span>{{$approvingPost->id}}</span>.
                                <a href="{{route('admin.posts.edit', $approvingPost) . '?approveFilters=' . request()->approveFilters}}" class="{{$cl}}">
                                    {{$approvingPost->title}}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <button type="submit" name="go_to_next" value="1" class="btn btn-success">Save and go to next</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary text-dark">Cancel</a>
        @else
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary text-dark">Cancel</a>
        @endif
    </form>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/posts.js')}}?v={{time()}}"></script>
    <script src="{{asset('/js/admin/post-category-selector.js')}}"></script>
    <script>
        $(document).ready(function () {
            // general logic of ajax form submit (supports files)
            $(document).on('click', '.approve-form button[type="submit"]', function (e) {
                e.preventDefault();
                loading();
                $('.input-error').empty();
                let form = $(this).closest('form');
                let formData = new FormData(form.get(0));

                if ($(this).attr('name')) {
                    formData.append($(this).attr('name'), $(this).attr('value'));
                }

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: (response)=>{
                        showServerSuccess(response);
                    },
                    error: function(response) {
                        swal.close();
                        showServerError(response);
                    }
                });
            })
        });
    </script>
@endpush

