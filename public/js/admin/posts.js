$(document).ready(function () {
    let selector = '#posts-table';
    let table = $(selector).DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        autoWidth: false,
        responsive: true,
        ajax: {
			url: $(selector).data('url') ? $(selector).data('url') : window.location.href,
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

    $('#activity-logs-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href,
			data: function (filter) {
                addTableFilters(filter);
			}
		},
        columns: [
            { data: 'id', name: 'id', searchable: false },
            { data: 'log_name', name: 'log_name' },
            { data: 'event', name: 'event' },
            { data: 'causer', name: 'causer'},
            { data: 'subject', name: 'subject'},
            { data: 'description', name: 'description'},
            { data: 'properties', name: 'properties', orderable: false, searchable: false},
            { data: 'created_at', name: 'created_at', searchable: false},
        ]
    });

    $(document).on('click', `${selector} .delete-resource`, function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});

    $('.start-approving').click(function(e) {
        e.preventDefault();

        let url = $(this).attr('href');
        let filters = {};
        addTableFilters(filters, $(selector).closest('.card'));

        $.ajax({
            url,
            type: 'get',
            data: {
                filters
            },
            success: (response)=>{
                showServerSuccess(response);
            },
            error: function(response) {
                showServerError(response);
            }
        });
    })
});
