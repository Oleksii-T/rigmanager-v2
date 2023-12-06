@extends('layouts.admin.app')

@section('title', 'Edit Category')

@section('content_header')
    <x-admin.title
        text="Edit Category #{{$category->id}}"
    />
@stop

@section('content')
    <form action="{{route('admin.categories.update', $category)}}" method="POST" class="general-ajax-submit">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <x-admin.multi-lang-input name="name" :model="$category" />
                            <span data-input="name" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <x-admin.multi-lang-input name="slug" :model="$category" />
                            <span data-input="slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group show-uploaded-file-name show-uploaded-file-preview">
                            <label>Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="image" name="image">
                                <label class="custom-file-label" for="image">{{$category->image->original_name ?? 'Choose File'}}</label>
                            </div>
                            <img src="{{$category->image->url??''}}" alt="" class="custom-file-preview">
                            <span data-input="image" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Parent Category</label>
                            <select class="form-control select2" name="category_id">
                                <option value="">Select Parent Category</option>
                                @foreach (\App\Models\Category::all() as $c)
                                    <option value="{{$c->id}}" @selected($category->category_id == $c->id)>{{$c->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="category_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fields</label>
                            <x-admin.multi-lang-input name="fields" :model="$category" textarea="1" />
                            <span data-input="fields" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Is Active</label>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_active" name="is_active" value="1" @checked($category->is_active)>
                                <label for="is_active" class="custom-control-label">Yes</label>
                            </div>
                            <span data-input="is_active" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary text-dark">Cancel</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Child categories</h3>
            </div>
            <div class="card-header">
                <div class="row">
                    <div class="col-lg-2">
                        <select class="table-filter form-control select2" name="parent">
                            <option value="">Parent Filter</option>
                            @foreach (\App\Models\Category::whereHas('childs')->get() as $c)
                                <option value="{{$c->id}}">{{$c->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select class="table-filter form-control" name="has_parent">
                            <option value="">Parent Presence Filter</option>
                            <option value="1">Is</option>
                            <option value="0">None</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select class="table-filter form-control" name="has_childs">
                            <option value="">Childs Presence Filter</option>
                            <option value="1">Is</option>
                            <option value="0">None</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <select class="table-filter form-control" name="status">
                            <option value="">Status Filter</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="categories-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="ids-column">ID</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Childs</th>
                            <th>Posts</th>
                            <th>Is Active</th>
                            <th>Created_at</th>
                            <th class="actions-column-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Posts</h3>
            </div>
            <div class="card-body">
                <table id="posts-table" class="table table-bordered table-striped">
                    <x-admin.posts-table />
                </table>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/categories.js')}}"></script>
    <script src="{{asset('/js/admin/posts.js')}}"></script>
@endpush
