@extends('layouts.admin.app')

@section('title', 'Edit mailer')

@section('content_header')
    <x-admin.title
        text="Edit mailer"
    />
@stop

@section('content')
    <form action="{{route('admin.mailers.update', $mailer)}}" method="POST" class="general-ajax-submit">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-header">
                <h5 class="m-0">Basic Info</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title</label>
                            <input name="title" type="text" class="form-control" value="{{$mailer->title}}">
                            <span data-input="title" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <input name="slug" type="text" class="form-control" value="{{$mailer->slug}}">
                            <span data-input="slug" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>User</label>
                            <select class="form-control select2" name="user_id">
                                @foreach (\App\Models\User::all() as $user)
                                    <option value="{{$user->id}}" @selected($mailer->user_id == $user->id)>{{$user->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="user_id" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Is Active</label>
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="is_active" name="is_active" value="1" @checked($mailer->is_active)>
                                <label for="is_active" class="custom-control-label">Yes</label>
                            </div>
                            <span data-input="is_active" class="input-error"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Search</label>
                            <input name="filters[search]" type="text" class="form-control" value="{{$mailer->getFilter('search')}}">
                            <span data-input="filters[search]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Author</label>
                            <select class="form-control select2" name="filters[author]">
                                <option value="">None</option>
                                @foreach (\App\Models\User::all() as $user)
                                    <option value="{{$user->id}}" @selected($mailer->getFilter('author') == $user->id)>{{$user->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="filters[author]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control select2" name="filters[category]">
                                <option value="">None</option>
                                @foreach (\App\Models\Category::all() as $category)
                                    <option value="{{$category->id}}" @selected($mailer->getFilter('category') == $category->id)>{{$category->name}}</option>
                                @endforeach
                            </select>
                            <span data-input="filters[author]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Conditions</label>
                            <select class="form-control select2" name="filters[conditions][]" multiple>
                                @foreach (\App\Models\Post::CONDITIONS as $item)
                                    <option value="{{$item}}" @selected(in_array($item, $mailer->getFilter('conditions')??[]))>{{readable($item)}}</option>
                                @endforeach
                            </select>
                            <span data-input="filters[conditions][]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Types</label>
                            <select class="form-control select2" name="filters[types][]" multiple>
                                @foreach (\App\Models\Post::TYPES as $item)
                                    <option value="{{$item}}" @selected(in_array($item, $mailer->getFilter('types')??[]))>{{readable($item)}}</option>
                                @endforeach
                            </select>
                            <span data-input="filters[types][]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Is Urgent</label>
                            <select class="form-control select2" name="filters[is_urgent][]" multiple>
                                <option value="1" @selected(in_array(1, $mailer->getFilter('is_urgent')??[]))>Yes</option>
                                <option value="0" @selected(in_array(0, $mailer->getFilter('is_urgent')??[]))>No</option>
                            </select>
                            <span data-input="filters[is_urgent][]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Countries</label>
                            <select class="form-control select2" name="filters[country]">
                                <option value="">None</option>
                                @foreach (\App\Models\Post::countries() as $key => $name)
                                    <option value="{{$key}}" @selected($mailer->getFilter('country') == $key)>{{$name}}</option>
                                @endforeach
                            </select>
                            <span data-input="filters[country]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Currency</label>
                            <select class="form-control" name="filters[currency]">
                                <option value="">None</option>
                                @foreach (currencies() as $key => $symbol)
                                    <option value="{{$key}}" @selected($mailer->getFilter('currency') == $key)>{{strtoupper($key)}}</option>
                                @endforeach
                            </select>
                            <span data-input="filters[currency]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cost from</label>
                            <input name="filters[cost_from]" type="text" class="form-control" value="{{$mailer->getFilter('cost_from')}}">
                            <span data-input="filters[cost_from]" class="input-error"></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Cost to</label>
                            <input name="filters[cost_to]" type="text" class="form-control" value="{{$mailer->getFilter('cost_to')}}">
                            <span data-input="filters[cost_to]" class="input-error"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success min-w-100">Save</button>
        <a href="{{ route('admin.mailers.index') }}" class="btn btn-outline-secondary text-dark min-w-100">Cancel</a>
    </form>
@endsection
