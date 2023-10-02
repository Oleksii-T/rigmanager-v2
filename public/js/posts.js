$(document).ready(function() {
    let images = ($('[page-data]').data('images') ?? []).reverse();
    let removedImages = [];
    let documents = [];
    let removedDocuments = [];
    let maxImages = 8;
    let maxDocuments = 8;

    /* show page */
    /*************/

    // init fancybox
    Fancybox.bind("[data-fancybox='postsgallery']", {
        // Your custom options
    });

    //hide/show translated/origin title/description
    $('.post-translated-text-toggle a').click(function(e){
        e.preventDefault();
        $('.prod-about h1, .prod-about p').toggleClass('hidden');
        $('.post-translated-text-toggle').toggleClass('d-none');
    });

    $('.execute-tba').click(function(e) {
        e.preventDefault();

        let url = $(this).data('url');

        swal.fire({
            title: window.Laravel.translations.ui_tba_modal.title,
            text: window.Laravel.translations.ui_tba_modal.text,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: window.Laravel.translations.ui_tba_modal.confirm,
            cancelButtonText: window.Laravel.translations.ui_tba_modal.cancel
        }).then((result) => {
            if (!result.value) {
                return;
            }

            fullLoader();

            $.ajax({
                url,
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response)=>{
                    fullLoader(false);
                    showServerSuccess(response);
                },
                error: function(response) {
                    fullLoader(false);
                    showServerError(response);
                }
            });
        });
        return;
    })

    /* create\edit page */
    /********************/

    // submit form
    $('#form-post').submit(function(e) {
        e.preventDefault();
        let form = $(this);
        let button = $(this).find('button[type=submit]');
        if (button.hasClass('cursor-wait')) {
            return;
        }
        button.addClass('cursor-wait');
        let formData = new FormData(this);
        let i = 0;
        images.forEach(img => {
            if (img.id) {
                formData.append(`old_images[${i}]`, img.id);
            } else {
                formData.append(`images[${i}]`, img.file);
            }
            i++;
        });
        removedImages.forEach(id => {
            formData.append('removed_images[]', id);
        });
        documents.forEach(doc => {
            formData.append('documents[]', doc);
        });
        removedDocuments.forEach(id => {
            formData.append('removed_documents[]', id);
        });

        $('.form-error').empty();
        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (response)=>{
                button.removeClass('cursor-wait');
                showServerSuccess(response);
            },
            error: function(response) {
                button.removeClass('cursor-wait');
                showServerError(response);
            }
        });
    })

    // categories select
    $(document).on('selectmenuchange', '.categories-level-selects .cat-lev-1 select', function (e) {
        console.log(`select new first categ`); //! LOG
        let wrpr = $(this).closest('.categories-level-selects');
        let val = $(this).val();
        console.log(` new val is `, val); //! LOG
        wrpr.find('input[name=category_id]').val(val);

        // hide all second and third level categories
        wrpr.find('.cat-lev-2 .select-block').addClass('d-none');
        wrpr.find('.cat-lev-3 .select-block').addClass('d-none');

        // show second level
        wrpr.find(`.cat-lev-2 .select-block[data-parentcateg="${val}"]`).removeClass('d-none');
    })
    $(document).on('selectmenuchange', '.categories-level-selects .cat-lev-2 select', function (e) {
        console.log(`select new second categ`); //! LOG
        let wrpr = $(this).closest('.categories-level-selects');
        let val = $(this).val();

        if (!val) {
            console.log(`empty val`); //! LOG
            val = wrpr.find('.cat-lev-1 select').val();
        }
        console.log(` new val is `, val); //! LOG

        wrpr.find('input[name=category_id]').val(val);

        // hide all third level categories
        wrpr.find('.cat-lev-3 .select-block').addClass('d-none');

        // show third level
        wrpr.find(`.cat-lev-3 .select-block[data-parentcateg="${val}"]`).removeClass('d-none');
    })
    $(document).on('selectmenuchange', '.categories-level-selects .cat-lev-3 select', function (e) {
        console.log(`select new third categ`); //! LOG
        let wrpr = $(this).closest('.categories-level-selects');
        let val = $(this).val();
        if (!val) {
            console.log(`empty val`); //! LOG
            val = wrpr.find('.cat-lev-2 .select-block:not(.d-none) select').val();
        }
        console.log(` new val is `, val); //! LOG
        wrpr.find('input[name=category_id]').val(val);
    })

    let el = document.getElementsByClassName('upload-images')[0] ?? null;
    if (el) {
        new Sortable(el, {
            draggable: "> div:not(:last-child)",
            chosenClass: 'transparent',
            sort: true,
            onChange: function(evt) {
                let sorted = [];
                $('.upload-images .upload-images_wrapper.user-image').each(function(i) {
                    let index = $(this).data('index')
                    sorted.unshift(images[index]);
                });
                images = sorted;
            }
        })
    }

    // toggle auto-translation edit logic
    $('.post-auto-translate-toggle').change(function(e) {
        e.preventDefault();
        let is = $(this).is(':checked');
        if (is) {

        }
        $(this).closest('form').find('input[type=text], textarea').prop('disabled', is);
        // $(this).closest('form').find('.form-button-block .button').toggleClass('d-none');
    })

    // submit post translations
    $('form.post-translations').submit(function(e){
        e.preventDefault();
        let form = $(this);
        let is = form.find('.post-auto-translate-toggle').is(':checked');
        let button = $(this).find('button[type=submit]');
        let formData = new FormData(this);
        if (button.hasClass('cursor-wait')) {
            return;
        }

        if (form.hasClass('show-full-loader')) {
            fullLoader();
        }

        if (is) {
            swal.fire({
                title: form.data('asktitle'),
                text: form.data('asktext'),
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: form.data('askyes'),
                cancelButtonText: form.data('askno')
            }).then((result) => {
                formData.append('rerun-translator', result.value?1:0);
                ajaxSubmit(form, formData, button);
            });
            return;
        }

        ajaxSubmit(form, formData, button);
    })

    // load drag&drop images
    $('.upload-images_empty').on('drop dragdrop',function(e){
        e.preventDefault();
        let dt = e.originalEvent.dataTransfer;
        if(!dt || !dt.files.length) {
            return;
        }
        handleImages([...dt.files]);
        showImages();
    });
    $('.upload-images_empty').on('dragenter',function(e){
        e.preventDefault();
    })
    $('.upload-images_empty').on('dragleave',function(){
    })
    $('.upload-images_empty').on('dragover',function(e){
        e.preventDefault();
    })

    // simulate images input click
    $('.upload-images_empty').click(function(e) {
        e.preventDefault();
        $('#images-multiple-input').trigger('click');
    })

    // save selected images
    $('#images-multiple-input').change(function() {
        handleImages([...this.files]);
        showImages();
    })

    // remove localy saved image
    $(document).on('click', '.upload-images_remove', function (e) {
        e.preventDefault();
        let i = $(this).closest('.upload-images_wrapper').data('index');
        let id = $(this).data('id');
        if (id) {
            removedImages.push(id);
        }
        images.splice(i, 1);
        showImages();
    })

    // save images localy
    function handleImages(files) {
        files.forEach(file => {
            if (images.length >= maxImages) {
                showToast('Images limit is reached', false);
                return;
            }
            let url = URL.createObjectURL(file);
            images.unshift({
                url: url,
                file: file
            });
        });
    }

    // visualize localy saved images to user
    function showImages() {
        let clone = $('.upload-images_wrapper.clone');
        let wrpr = $('.upload-images');
        let i = 0;
        wrpr.find('.upload-images_wrapper.user-image').remove();
        images.forEach(image => {
            let imageEl = clone.clone()
                .removeClass('clone')
                .removeClass('hidden')
                .data('index', i)
                .prependTo(wrpr);
            imageEl.find('img.preview').attr('src', image.url);
            imageEl.find('.upload-images_remove').data('id', image.id);
            i++;
        });
    }

    // load drag&drop images
    $('.upload-docs_empty').on('drop dragdrop',function(e){
        e.preventDefault();
        let dt = e.originalEvent.dataTransfer;
        if(!dt || !dt.files.length) {
            return;
        }
        handleDocuments([...dt.files]);
        showDocuments();
    });
    $('.upload-docs_empty').on('dragenter',function(e){
        e.preventDefault();
    })
    $('.upload-docs_empty').on('dragleave',function(){
    })
    $('.upload-docs_empty').on('dragover',function(e){
        e.preventDefault();
    })

    // simulate images input click
    $('.upload-docs_empty').click(function(e) {
        e.preventDefault();
        $('#documents-multiple-input').trigger('click');
    })

    // save selected documents
    $('#documents-multiple-input').change(function() {
        handleDocuments([...this.files]);
        showDocuments();
    })

    // remove localy saved document
    $(document).on('click', '.upload-documents_remove', function (e) {
        e.preventDefault();
        let i = $(this).closest('.upload-documents_wrapper').data('index');
        let id = $(this).data('id');
        if (id) {
            removedDocuments.push(id);
        }
        documents.splice(i, 1);
        showDocuments();
    })

    // save documents localy
    function handleDocuments(files) {
        files.forEach(file => {
            if (documents.length >= maxDocuments) {
                showToast('Documents limit is reached', false);
                return;
            }
            documents.unshift(file);
        });
    }

    // visualize localy saved documents to user
    function showDocuments() {
        let clone = $('.upload-documents_wrapper.clone');
        let wrpr = $('.upload-documents');
        let i = 0;
        wrpr.find('.upload-documents_wrapper.user-image').remove();
        documents.forEach(file => {
            let docEl = clone.clone()
                .removeClass('clone')
                .removeClass('hidden')
                .data('index', i)
                .prependTo(wrpr)
                .find('.upload-images_name')
                .text(file.name);
                docEl.find('.upload-documents_remove').data('id', file.id);
            i++;
        });
    }

    /* my posts page */
    /*****************/
    let selectedAll = false;
    let selected = [];

    $('#check-all').change(function(e) {
        e.preventDefault();
        let val = $(this).is(':checked');
        let checks = $('.catalog-item .check-item input');
        selectedAll = val;
        selected = [];
        checks.each(function(i) {
            $(this).prop('checked', val);
        });
        let total = val ? $('.searched-amount').text() : selected.length;
        $('.selected-posts-count').text(total);
    })

    $(document).on('click', '.catalog-item .check-item input', function (e) {
        let id = $(this).closest('.catalog-item').data('id');
        let i = selected.indexOf(id);
        let found = i > -1;
        if (found) {
            selected.splice(i, 1);
        } else {
            selected.push(id);
        }
        if (selectedAll) {
            $('#check-all').prop('checked', false);
            let total = +$('.searched-amount').text();
            $('.selected-posts-count').text(total-selected.length);
        } else {
            $('.selected-posts-count').text(selected.length);
        }
    })

    // visualize checked selected posts
    $(document).on('posts:filtered', function(e) {
        e.preventDefault();
        $('.catalog-item').each(function(i) {
            let id = $(this).data('id');
            let input = $(this).find('.check-item input');
            let found = selected.indexOf(id) > -1;
            if (selectedAll && !found) {
                input.prop('checked', true);
            } else if (!selectedAll && found) {
                input.prop('checked', true);
            }
        });
    });

    $('.apply-selected-action').on('selectmenuchange', function(e) {
        e.preventDefault();
        let val = $(this).val();
        if (!val) {
            return;
        }
        $(this).val('');
        $(this).selectmenu('refresh');
        fullLoader();

        let filters = params;
        filters.category = $('[page-data]').data('categoryid');

        $.ajax({
            url: '/profile/posts/action',
            type: 'post',
            data: {
                action: val,
                all: +selectedAll,
                selected: selected,
                filters: filters,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response)=>{
                showToast(response.message);
                $(document).trigger('posts:filter');
            },
            error: function(response) {
                showServerError(response);
            }
        });
    })

    $(document).on('click', '.bar-view', function (e) {
        e.preventDefault();
        fullLoader();
        let url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'post',
            data: {
                _method: 'PUT',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response)=>{
                showToast(response.message);
                $(document).trigger('posts:filter');
            },
            error: function(response) {
                showServerError(response);
            }
        });
    })

    $(document).on('click', '.bar-delete', async function (e) {
        e.preventDefault();
        let url = $(this).attr('href');

        let res = await swal.fire({
            title: 'Are you sure?',//! TRANSLATE
            text: "You won't be able to revert this!",//! TRANSLATE
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'//! TRANSLATE
        });

        if (!res.isConfirmed) {
            return;
        }

        fullLoader();

        $.ajax({
            url: url,
            type: 'post',
            data: {
                _method: 'DELETE',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response)=>{
                showToast(response.message);
                $(document).trigger('posts:filter');
            },
            error: function(response) {
                showServerError(response);
            }
        });
    })

    $(document).on('click', '.show-post-views', function (e) {
        e.preventDefault();
        let url = $(this).attr('href');
        fullLoader();

        $.ajax({
            url: url,
            type: 'get',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response)=>{
                fullLoader(false);
                swal.fire({
                    html: response.data
                });
            },
            error: function(response) {
                showServerError(response);
            }
        });
    })

    /* favorites page */
    /*****************/
    $('.clear-favs').click(async function(e) {
        e.preventDefault();
        let url = $(this).attr('href');

        let res = await swal.fire({
            title: 'Are you sure?',//! TRANSLATE
            text: "All Favorite Posts will be cleared",//! TRANSLATE
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, clear Favorites!'//! TRANSLATE
        });

        if (!res.isConfirmed) {
            return;
        }

        fullLoader();

        $.ajax({
            url: url,
            type: 'post',
            data: {
                _method: 'PUT',
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response)=>{
                window.location.reload();
            },
            error: function(response) {
                showServerError(response);
            }
        });
    })
});
