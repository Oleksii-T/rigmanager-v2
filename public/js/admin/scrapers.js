$(document).ready(function () {
    let scrapersTable = $('#scrapers-table').DataTable({
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

    let scraperRunsTable = $('#scraper-runs-table').DataTable({
        // order: [[ 0, "desc" ]],
        serverSide: true,
        columns: [
            { data: 'id', name: 'id' },
            // { data: 'status', name: 'status'},
            // { data: 'posts', name: 'posts'},
            // { data: 'created_at', name: 'created_at'},
            // { data: 'end_at', name: 'end_at' },
            // { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '#scrapers-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(scrapersTable, $(this).data('link'));
    });

    // $(document).on('click', '#scraper-runs-table .delete-resource', function (e) {
    //     e.preventDefault();
    //     deleteResource(scraperRunsTable, $(this).data('link'));
    // });

    $('.add-selector').click(function(e) {
        e.preventDefault();
        let clone = $('.og-selector').last().clone().removeClass('.og-selector');
        clone.find('input').val('');
        clone.find('.remove-wraper').removeClass('d-none');
        let oldIndex = +clone.find('input').last().attr('name').replace(/\D+/g, '');
        let newIndex = oldIndex + 1
        clone.find('[name]').each(function(i) {
            $(this).attr('name', $(this).attr('name').replace(oldIndex, newIndex))
        });
        clone.find('[type="checkbox"]').each(function(i) {
            $(this).attr('value', 1)
        });
        $('.selectors-wraper').append(clone);
    })

    $(document).on('click', '.remove-selector', function (e) {
        $(this).closest('.row').remove();
    })
});
