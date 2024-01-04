$(document).ready(function () {
    let selector = '#subscriptions-table';
    let table = $(selector).DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: $(selector).data('url') ? $(selector).data('url') : window.location.href,
		},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'user', name: 'user' },
            { data: 'plan', name: 'plan' },
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', selector+' .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

    $('#subscription-cycles-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
            url: window.location.href
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'invoice', name: 'invoice' },
            { data: 'price', name: 'price' },
            { data: 'created_at', name: 'created_at' },
            { data: 'expire_at', name: 'expire_at' },
            { data: 'is_active', name: 'is_active' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
