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

    $('.posts-rich-desc').summernote({
        minHeight: '140px',
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['para', ['ul', 'ol']],
            ['table', ['table']],
            ['misc', ['undo', 'redo']],
            ['admin', ['codeview', 'htmlformat', 'htmlminify']]
        ],
        buttons: {
            htmlformat: function(context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="fa fa-paint-brush"/>',
                    tooltip: 'Format HTML',
                    click: function() {
                        let code = context.code();
                        const options = { indent_size: 2, space_in_empty_paren: true }
                        let formatted = html_beautify(code, options);
                        context.code(formatted);
                        showToast('Inner HTML formatted!');
                    }
                });
                return button.render();
            },
            htmlminify: function(context) {
                var ui = $.summernote.ui;
                var button = ui.button({
                    contents: '<i class="fa fa-eraser"/>',
                    tooltip: 'Minify HTML',
                    click: function() {
                        let code = context.code();
                        let minified = code.replace(/\t|\s{2,}/g, '');
                        context.code(minified);
                        showToast('Inner HTML minified!');
                    }
                });
                return button.render();
            }
        },
        styleTags: ['p', 'h2', 'h3', 'h4'],
        disableDragAndDrop: true,
        codeviewIframeFilter: true,
        callbacks: {
            onFocus: () => $('.note-editor').addClass('focused'),
            onBlur: () => $('.note-editor').removeClass('focused')
        },
        codemirror: {
            theme: 'monokai'
        }
    });

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
