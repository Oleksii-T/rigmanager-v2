$(document).ready(function () {
    let table = $('#exchange-rates-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href
		},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'from', name: 'from' },
            { data: 'to', name: 'to' },
            { data: 'cost', name: 'cost' },
            { data: 'auto_update', name: 'auto_update' },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '#exchange-rates-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });
});
