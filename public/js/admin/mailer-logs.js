$(document).ready(function () {
    let selector = '#mailer-logs-table';

    let table = $(selector).DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href,
			data: function (filter) {
                addTableFilters(filter);
			}
		},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'mailer', name: 'mailer' },
            { data: 'posts', name: 'posts' },
            { data: 'filters', name: 'filters' },
            { data: 'created_at', name: 'created_at', searchable: false },
        ]
    });

	$('.table-filter').change(function() {
		table.draw();
	});
});
