$(document).ready(function () {
    let table = $('#mailers-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href,
			data: function (filter) {
				filter.role = $('.table-filter[name=role]').val();
			}
		},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user', name: 'user' },
            { data: 'title', name: 'title' },
            { data: 'is_active', name: 'is_active' },
            { data: 'posts', name: 'posts' },
            { data: 'last_at', name: 'last_at', searchable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '#mailers-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});
});
