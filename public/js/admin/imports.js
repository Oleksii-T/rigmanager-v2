$(document).ready(function () {
    let selector = '#imports-table';
    $(selector).DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        autoWidth: false,
        responsive: true,
        ajax: {
			url: $(selector).data('url') ? $(selector).data('url') : window.location.href,
		},
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'user', name: 'user', orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'posts', name: 'posts', searchable: false },
            { data: 'created_at', name: 'created_at', searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
