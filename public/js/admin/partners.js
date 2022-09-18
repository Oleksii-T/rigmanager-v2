$(document).ready(function () {
    let table = $('#partners-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href
		},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user', name: 'user' },
            { data: 'image', name: 'image', orderable: false, searchable: false  },
            { data: 'link', name: 'link'},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '#partners-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});
});
