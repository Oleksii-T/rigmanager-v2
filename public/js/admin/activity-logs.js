$(document).ready(function () {

    let table = $('#activity-logs-table').DataTable({
        order: [[ 0, "desc" ]],
        serverSide: true,
        pageLength: 100,
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

    $(document).on('click', '#activity-logs-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });

	$('.table-filter').change(function() {
		table.draw();
	});

    $('.has-sub-select').change(function(e) {
        e.preventDefault();
        let name = $(this).attr('name');
        let els = $(`[data-parentselect="${name}"]`);
        let val = escapeSelector($(this).val());
        let childSelectors = $(`[data-parentselect="${name}"][data-parentvalue="${val}"]`);
        els.addClass('d-none');

        // reset all child selectors
        childSelectors.each(function(index) {
            $(this).val(''); // .find('option')
        });

        // show
        if (val) {
            childSelectors.removeClass('d-none');
        }
    })

});

function escapeSelector(s) {
    return s.replace(/(:|\.|\[|\]|,|=|@|\\)/g, "\\$1");
  }
