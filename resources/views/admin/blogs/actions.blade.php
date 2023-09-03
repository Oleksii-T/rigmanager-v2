{{-- Display general actions for admin panel data tables. Default actions is 'show' and 'destroy' --}}
<div class="table-actions d-flex align-items-center">
    <a href="{{route("admin.blogs.edit", $model)}}" class="btn btn-info btn-sm mr-1">Edit</a>
    <button data-link="{{route("admin.blogs.destroy", $model)}}" type="button" class="delete-resource btn btn-danger btn-sm mr-1">Delete</button>
</div>
