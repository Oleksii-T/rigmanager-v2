$(document).ready(function () {
    let table = $('#faqs-table').DataTable({
        order: [[ 0, "asc" ]],
        serverSide: true,
        ajax: {
			url: window.location.href
		},
        columns: [
            { data: 'order', name: 'order'},
            { data: 'question', name: 'question'},
            { data: 'answer', name: 'answer'},
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $(document).on('click', '#faqs-table .delete-resource', function (e) {
        e.preventDefault();
        deleteResource(table, $(this).data('link'));
    });
});
