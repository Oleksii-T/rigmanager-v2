$(document).ready(function () {
    $(document).on('change', '.categories-level-selects .cat-lev-1 select', function (e) {

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
