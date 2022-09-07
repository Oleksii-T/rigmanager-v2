$(document).ready(function() {
    let images = [];
    let removedImages = [];
    let documents = [];
    let removedDocuments = [];
    let maxImages = 8;
    let maxDocuments= 8;

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

    //count views
    let id = $('[post-data]').data('id');
    if (id) {
        $.ajax({
            type: "POST",
            url: `/posts/${id}/view`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
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
            if (documents.length >= maxImages) {
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
});
