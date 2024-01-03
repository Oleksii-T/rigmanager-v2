let filters = {};

$(document).ready(function () {
    if ($('.messages-content').length) {
        getContent();
    }

    $(document).on('change', '.table-filter', function (e) {
        let val = $(this).val();
        let name = $(this).attr('name');
        filters[name] = val;

        getContent();
	});

    let selector = '#messages-table';

    let table = $(selector).DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: $(selector).data('url') ? $(selector).data('url') : window.location.href,
			data: function (filter) {
                addTableFilters(filter);
			}
		},
        columns: [
            { data: 'id', name: 'id'},
            { data: 'user', name: 'user'},
            { data: 'message', name: 'message'},
            { data: 'is_read', name: 'is_read'},
            { data: 'created_at', name: 'created_at'},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', selector+' .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});
});

function getContent() {
    loading();
    $.ajax({
        data: filters,
        success: (response)=>{
            swal.close();
            if (!response.success) {
                swal.fire('Error!', response.message, 'error');
                return;
            }

            $('.messages-content').html(response.data.html);
        },
        error: function(response) {
            swal.close();
            showServerError(response);
        }
    });
}
