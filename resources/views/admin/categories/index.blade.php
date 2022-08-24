@extends('layouts.admin.app')

@section('title', 'Categorys')

@section('content_header')
    <x-admin.title
        text="Categorys"
        :button="['+ Add Category', route('admin.categories.create')]"
        bcRoute="admin.categories.index"
    />
@stop

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
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
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('/js/admin/categories.js')}}"></script>
@endpush
