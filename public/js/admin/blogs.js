$(document).ready(function () {
    let table = $('#blogs-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href,
			data: function (filter) {
                addTableFilters(filter);
			}
		},
        columns: [
            { data: 'id', name: 'id'},
            { data: 'thumbnail', name: 'thumbnail'},
            { data: 'title', name: 'title'},
            { data: 'status', name: 'status'},
            { data: 'posted_at', name: 'posted_at'},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '#blogs-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});
});
