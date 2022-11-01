$(document).ready(function () {
    let selector = '#categories-table';
    let table = $(selector).DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href,
			data: function (filter) {
                addTableFilters(filter, $(selector).closest('.card'));
			}
		},
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'name', name: 'name', orderable: false, searchable: false },
            { data: 'parent', name: 'parent', orderable: false, searchable: false },
            { data: 'childs', name: 'childs', searchable: false },
            { data: 'posts', name: 'posts', searchable: false },
            { data: 'is_active', name: 'is_active', searchable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', `${selector} .delete-resource`, function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});
});
