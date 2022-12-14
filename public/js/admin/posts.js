$(document).ready(function () {
    let selector = '#posts-table';
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
            { data: 'id', name: 'id' },
            { data: 'title', name: 'title' },
            { data: 'user', name: 'user' },
            { data: 'category', name: 'category' },
            { data: 'status', name: 'status' },
            { data: 'is_active', name: 'is_active' },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'updated_at', name: 'updated_at', searchable: false },
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
