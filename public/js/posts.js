$(document).ready(function() {
    let images = [];
    let removedImages = [];
    let documents = [];
    let removedDocuments = [];
    let maxImages = 8;
    let maxDocuments = 8;

    /* show page */
    /*************/

    //count views
    let id = $('[page-data]').data('postid');
    if (id) {
        $.ajax({
            type: "POST",
            url: `/posts/${id}/view`,
            data: {
                _method: 'PUT',
                _token: $('meta[name=csrf-token]').attr('content')
            },
        });
    }

    // user click add author to mailer btn
    $('.add-to-mailer').click(function(e) {
        e.preventDefault();
        var button = $(this);
        button.addClass('loading');
        $.ajax({
            type: "GET",
            url: "{{ route('mailer.create.by.author', $post->user_id) }}",
            success: function(data) {
                try {
                    data = JSON.parse(data);
                    if ( data.code == 200 ) {
                        showPopUpMassage(true, data.message);
                        button.text("{{ __('ui.mailerAuthorAlreadyAdded') }}");
                    } else {
                        showPopUpMassage(false, data.message);
                    }
                } catch (error) {
                    showPopUpMassage(false, "{{ __('messages.error') }}");
                }
                button.removeClass('loading');
            },
            error: function() {
                showPopUpMassage(false, "{{ __('messages.error') }}");
                button.removeClass('loading');
            }
        });
    });

    //hide/show translated/origin title/description
    $('.show-origin-text').click(function(e){
        e.preventDefault();
        $('.prod-about h1, .prod-about p').toggleClass('hidden');
    });

    //show modal contacts
    $('.show-contacts').click(function(){
        var button = $(this);
        button.addClass('loading');
        $.ajax({
            type: "get",
            url: `/posts/${id}/contacts`,
            success: function(response) {
                button.removeClass('loading');
                $('.open-contacts').trigger('click');
                $('.contact-email .prod-info-text').text(response.data.email);
                $('.contact-phone .prod-info-text').text(response.data.phone);
            },
            error: function(response) {
                button.removeClass('loading');
                showToast(response.responseJSON.message, false);
            }
        });
    });

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
        images.forEach(img => {
            formData.append('images[]', img.file);
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

    $('.upload-images').sortable({
        items: '> div:not(:last-child)',
        stop: function() {
            let sorted = [];
            $('.upload-images .upload-images_wrapper.user-image').each(function(i) {
                let index = $(this).data('index')
                sorted.unshift(images[index]);
            });
            images = sorted;
            showImages();
        }
    });

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
        wrpr.find('.upload-images_wrapper.user-image').remove();
        let i = 0;
        images.forEach(image => {
            let imageEl = clone.clone()
                .removeClass('clone')
                .removeClass('hidden')
                .data('index', i)
                .prependTo(wrpr);
            imageEl.find('img.preview').attr('src', image.url);
            i++;
        });
    }

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

    // load drag&drop documents
    $('.edit-doc-button').on('drop dragdrop',function(e){
        e.preventDefault();
        let dt = e.originalEvent.dataTransfer;
        if(!dt || !dt.files.length) {
            return;
        }
        handleDocuments([...dt.files]);
        showDocuments();
    });
    $('.edit-doc-button').on('dragenter',function(e){
        e.preventDefault();
    })
    $('.edit-doc-button').on('dragleave',function(){
    })
    $('.edit-doc-button').on('dragover',function(e){
        e.preventDefault();
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
        wrpr.empty();
        documents.forEach(file => {
            clone.clone()
                .removeClass('clone')
                .removeClass('hidden')
                .data('index', i)
                .prependTo(wrpr)
                .find('.doc-name')
                .text(file.name);
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
});
