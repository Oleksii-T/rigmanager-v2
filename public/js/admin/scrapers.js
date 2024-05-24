$(document).ready(function () {
    let table = $('#scrapers-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href
		},
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'user', name: 'user' },
            { data: 'runs', name: 'runs'},
            { data: 'created_at', name: 'created_at'},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#scraper-runs-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'posts', name: 'posts'},
            { data: 'created_at', name: 'created_at'},
            { data: 'end_at', name: 'end_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '#scrapers-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});

    $('.add-selector').click(function(e) {
        e.preventDefault();
        let clone = $('.og-selector').last().clone().removeClass('.og-selector');
        clone.find('input').val('');
        clone.find('.remove-wraper').removeClass('d-none');
        $('.selectors-wraper').append(clone);
    })

    $(document).on('click', '.remove-selector', function (e) {
        console.log(`remove`); //! LOG
        $(this).closest('.row').remove();
    })
});
