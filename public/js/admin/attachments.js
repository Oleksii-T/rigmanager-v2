$(document).ready(function () {
    let table = $('#attachments-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href
		},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'original_name', name: 'original_name' },
            { data: 'preview', name: 'preview', orderable: false, searchable: false  },
            { data: 'resource', name: 'resource'},
            { data: 'created_at', name: 'created_at', searchable: false},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '#attachments-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});
});
