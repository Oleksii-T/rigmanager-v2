$(document).ready(function () {
    $('.summernote').summernote();
    $('.select2').select2();
    $('.select2-tags').select2({
        tags: true
    });

    initDatePicker($('.daterangepicker-mult'));
    $('.daterangepicker-single').daterangepicker({
        singleDatePicker: true,
        timePicker: true,
        timePicker24Hour: true,
        showDropdowns: true,
        minYear: 2023,
        maxYear: parseInt(moment().format('YYYY'),10),
        locale: {
            format: 'YYYY-MM-DD HH:mm'
        }
    });

    // general logic of ajax form submit (supports files)
    $(document).on('submit', 'form.general-ajax-submit', function (e) {
        e.preventDefault();
        loading();
        let form = $(this);
        let formData = new FormData(this);
        $('.input-error').empty();

        $.ajax({
            url: form.attr('action'),
            type: form.attr('method'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: (response)=>{
                showServerSuccess(response);
            },
            error: function(response) {
                swal.close();
                showServerError(response);
            }
        });
    })

    // show uploaded file name
    $(document).on('change', '.show-uploaded-file-name input', function (e) {
        let name = $(this).val().split('\\').pop();
        $(this).closest('.show-uploaded-file-name').find('.custom-file-label').text(name);
    })

    // show uploaded file preview
    $(document).on('change', '.show-uploaded-file-preview input', function (e) {
        const [file] = this.files;
        if (file) {
            let el = $(this).closest('.show-uploaded-file-preview').find('.custom-file-preview');
            el.removeClass('d-none');
            el.attr('src', URL.createObjectURL(file));
        }
    })

    // trigger element by click manuly
    $('[data-trigger]').click(function() {
        $($(this).data('trigger')).trigger('click');
    });

    // copy text
    $('[data-copy]').click(function(e) {
        e.preventDefault();
        let text = $($(this).data('copy')).text();
        let message = $(this).data('message');
        var dummy = document.createElement("textarea");
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
        Toast.fire({
            icon: 'success',
            title: message??'Copied successfully'
        });
    });

    // add input for multi-file form block
    $('.add-file-input').click(function(e) {
        e.preventDefault();
        $(this)
            .closest('.card')
            .find('.file-input.clone')
            .clone()
            .appendTo($(this).closest('.card').find('.row'))
            .removeClass('d-none')
            .removeClass('clone');
    });

    // trigger click for multi-file form block to add first input automaticaly
    $('.add-file-input.auto-add').each(function(i) {
        $(this).trigger('click');
    });

    // remove input for multi-file form block
    $(document).on('click', '.delete-file-input', function (e) {
        e.preventDefault();
        let el = $(this).closest('.file-input');
        let url = $(this).data('url');
        console.log(url);
        if (!url) {
            el.remove();
            return;
        }
        loading();

        $.ajax({
            url: url,
            type: 'post',
            data: {
                _method: 'DELETE',
                _token: $('meta[name=csrf-token]').attr('content')
            },
            success: (response)=>{
                el.remove();
                swal.close();
            },
            error: function(response) {
                showServerError(response);
            }
        });

    })
});

function initDatePicker(el) {
    el.daterangepicker({
        startDate: moment().subtract(29, 'days'),
        endDate: moment(),
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment()],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Past Month': [moment().subtract(1, 'month'), moment()],
            'This Year': [moment().startOf('year'), moment()],
            'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            'Past Years': [moment().subtract(1, 'year'), moment()],
        },
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
}

// flash notification
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

//delete resource from datatable
function deleteResource(dataTable, url) {
    swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: 'delete',
                data: {
                    _token: $("[name='csrf-token']").attr("content")
                },
                success: (response)=>{
                    if (response.success) {
                        Toast.fire({
                            icon: 'success',
                            title: response.message
                        });
                        dataTable.draw();
                    } else {
                        swal.fire("Error!", response.message, 'error');
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    swal.fire("Error!", '', 'error');
                }
            });
        }
    });
}

// general error logic, after ajax form submit been processed
function showServerError(response) {
    if (response.status == 422) {
        for ([field, value] of Object.entries(response.responseJSON.errors)) {
            let errorElement = findErrorElementForField(field);
            let errorText = '';

            if (typeof value === 'string' || value instanceof String) {
                errorText = value;
            } else {
                for (const [key, error] of Object.entries(value)) {
                    errorText = errorText ? errorText+'<br>'+error : error;
                }
            }

            errorElement.html(errorText);
        }
    } else {
        swal.fire('Error!', response.responseJSON.message, 'error');
    }
}

// find element for 422 error visualization
function findErrorElementForField(field) {
    let findElHelper = function(field) {
        let errorElement = $(`.input-error[data-input=${field}]`);
        errorElement = errorElement.length ? errorElement : $(`.input-error[data-input="${field}[]"]`);
        errorElement = errorElement.length ? errorElement : $(`[name=${field}]`).closest('.form-group').find('.input-error');
        errorElement = errorElement.length ? errorElement : $(`[name="${field}[]"]`).closest('.form-group').find('.input-error');
        return errorElement;
    }

    let dotI = field.indexOf('.');

    if (dotI == -1) {
        return findElHelper(field);
    }

    fieldEscaped = field.replace('.', '\\.');
    let errorElement = findElHelper(fieldEscaped);

    if (errorElement.length) {
        return errorElement;
    }

    return findElHelper(field.slice(0, dotI));
}

// general success logic, after ajax form submit been processed
function showServerSuccess(response) {
    if (response.success) {
        if (response.data?.redirect) {
            swal.fire("Success!", response.message, 'success').then((result) => {
                window.location.href = response.data.redirect;
            });
            return;
        }
        if (response.data?.reaload) {
            swal.fire("Success!", response.message, 'success').then((result) => {
                window.location.reload();
            });
            return;
        }
        Toast.fire({
            icon: 'success',
            title: response.message
        });
    } else {
        swal.fire("Error!", response.message, 'error');
    }
}

//show loading unclosable popup
function loading(text='Request processing...') {
    swal.fire({
        title: 'Wait!',
        text: text,
        showConfirmButton: false,
        allowOutsideClick: false
    });
}

// add table-filters to data-table request
function addTableFilters(data, wraper=null) {
    let selector = '.table-filter';
    let filters = wraper ? wraper.find(selector) : $(selector);

    filters.each(function(i) {
        let name = $(this).attr('name');
        let val = $(this).val();
        data[name] = val;
    });
}
