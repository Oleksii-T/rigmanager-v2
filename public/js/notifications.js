let data = {
    page: 1,
    level: null,
    from: null,
    to: null,
    _token: $('meta[name="csrf-token"]').attr('content')
}

$(document).ready(function () {

    // read notification in profile section
    $(document).on('submit', '.notification-read', function (e) {
        e.preventDefault();
        let form = $(this);
        let button = $(this).find('button[type=submit]');
        let formData = new FormData(this);
        if (button.hasClass('cursor-wait')) {
            return;
        }
        ajaxSubmit(form, formData, button, function(response) {
            form.remove();
            showToast(response.message);
        });
    })

    // table filter
    $('.notif-table-filter').on('apply.daterangepicker', function(ev, picker) {
        let f = 'YYYY-MM-DD';
        data.from = picker.startDate.format(f);
        data.to = picker.endDate.format(f);
        data.page = 1;
        reloadNotifications();
    });

    $('.notif-table-filter').change(function(e) {
        e.preventDefault();
        let name = $(this).attr('name');
        if (!name) {
            return;
        }
        console.log(` change filter`, name, $(this).val()); //! LOG
        data[name] = $(this).val();
        data.page = 1;
        reloadNotifications();
    })

    // pagination
    $(document).on('click', '.pagination-field a', function (e) {
        e.preventDefault();
        if ($(this).attr('rel') == 'next') {
            data.page++;
        } else if ($(this).attr('rel') == 'prev') {
            data.page--;
        } else {
            data.page = $(this).html();
        }
        reloadNotifications();
    })
});

function reloadNotifications() {
    fullLoader();
    $.ajax({
        data,
        success: (response)=>{
            fullLoader(false);
            $('.notifications-wrpr').empty();
            $('.notifications-wrpr').append(response.data.html);
        },
        error: function(response) {
            fullLoader(false);
            showServerError(response);
        }
    });
}
