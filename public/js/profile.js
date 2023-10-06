$(document).ready(function() {
    $('#add-cts-email').click(function(e) {
        e.preventDefault();
        let wraper = $('#cts-email-inputs');
        let element = wraper.children().first();
        let clone = element.clone();
        clone.find('input').removeAttr('placeholder').val('');
        clone.appendTo(wraper);
        clone.find('.form-error').html('');
        resetErrorNames(wraper);
    })

    $(document).on('click', '.delete-cts-email', function (e) {
        e.preventDefault();
        let wraper = $('#cts-email-inputs');
        let count = wraper.children().length;
        let el = $(this).closest('.cts-el');

        if (count - 1 == 0) {
            if (!el.find('input').val()) {
                showToast(trans('messages_profile_canNotDeleteLastEmailContact'), false);
            }
            el.find('input').val('');
        } else {
            el.remove();
        }

        wraper.children().first().find('input').attr('placeholder', wraper.data('defaultph'));
        resetErrorNames(wraper);
    });

    $('#add-cts-phone').click(function(e) {
        e.preventDefault();
        let wraper = $('#cts-phone-inputs');
        let element = wraper.children().first();
        if (element.hasClass('d-none')) {
            element.removeClass('d-none');
            return;
        }
        let clone = element.clone();
        clone.find('input').removeAttr('placeholder').val('');
        clone.appendTo(wraper);
        clone.find('.form-error').html('');
        resetErrorNames(wraper, false);
    });

    $(document).on('click', '.delete-cts-phone', function (e) {
        let wraper = $('#cts-phone-inputs');
        let el = $(this).closest('.cts-el');
        let count = wraper.children().length;

        if (count - 1 == 0) {
            el.find('input').val('');
            el.addClass('d-none');
        } else {
            el.remove();
        }

        wraper.children().first().find('input').attr('placeholder', wraper.data('defaultph'));
        resetErrorNames(wraper, false);
    });

});

function resetErrorNames(wraper, emails=true) {
    let i = 0;
    let text = emails ? 'info.emails.' : 'info.phones.' ;
    wraper.children().each(function(index) {
        $(this).find('.form-error').get(0).dataset.input = text+i;
        console.log(` added error name`, text+i); //! LOG
        i++;
    });
}
