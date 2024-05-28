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

    // categories select
    $(document).on('change', '.categories-level-selects .cat-lev-1 select', function (e) {
        console.log(`new level 1 `); //! LOG

        let wrpr = $(this).closest('.categories-level-selects');
        let val = $(this).val();
        wrpr.find('input[name=category_id]').val(val);

        // hide all second and third level categories
        wrpr.find('.cat-lev-2 .select-block').addClass('d-none');
        wrpr.find('.cat-lev-3 .select-block').addClass('d-none');

        // show second level
        wrpr.find(`.cat-lev-2 .select-block[data-parentcateg="${val}"]`).removeClass('d-none');
    })
    $(document).on('change', '.categories-level-selects .cat-lev-2 select', function (e) {
        let wrpr = $(this).closest('.categories-level-selects');
        let val = $(this).val();

        if (!val) {
            val = wrpr.find('.cat-lev-1 select').val();
        }

        wrpr.find('input[name=category_id]').val(val);

        // hide all third level categories
        wrpr.find('.cat-lev-3 .select-block').addClass('d-none');

        // show third level
        wrpr.find(`.cat-lev-3 .select-block[data-parentcateg="${val}"]`).removeClass('d-none');
    })
    $(document).on('change', '.categories-level-selects .cat-lev-3 select', function (e) {
        let wrpr = $(this).closest('.categories-level-selects');
        let val = $(this).val();
        if (!val) {
            val = wrpr.find('.cat-lev-2 .select-block:not(.d-none) select').val();
        }
        wrpr.find('input[name=category_id]').val(val);
    })

});
