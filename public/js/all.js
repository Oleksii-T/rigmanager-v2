$(document).ready(function () {
    $('.select2').select2();
    let urlSearchParams = new URLSearchParams(window.location.search);

    // autocomplete global search
    $('.typeahead-input').typeahead(
        {
            // hint: true,
            highlight: true,
            minLength: 1
        },
        {
            limit: 50,
            source: function (search, cb, acb) {
                $.ajax({
                    url: '/search-autocomplete',
                    data: {search},
                    success: (response) => acb(response),
                });
            }
        }
    );

    // init user friendly title tooltips
    $( document ).tooltip({
        position: {
            my: 'center bottom-5',
            at: 'center top',
            collision: 'flipfit'
        }
    });

	// scroll header
	$(window).scroll( function(){
		if ($(this).scrollTop() > 50){
			$('.header').addClass('fixed');
		}else{
			$('.header').removeClass('fixed');
		}
	});
	if ($(window).scrollTop() > 50){
		$('.header').addClass('fixed');
	}

    // init fancybox for profile and info meny
    Fancybox.bind("[data-fancybox='mobilemenu']", {
        mainClass: 'mobile-fancybox-wrapper'
    });

    //block not-ready links
    $('.not-ready').click(function(e){
        e.preventDefault();
        showToast(trans('messages_inProgress'), false);
    });

	// mob nav
	$(document).on('click','.mob-nav-icon',function(e){
		e.preventDefault();
		if($(this).hasClass('active')){
			$('.mob-nav').removeClass('vis');
			$('.mob-nav-icon').removeClass('active');
		}else{
			$('.mob-nav').addClass('vis');
			$('.mob-nav-icon').addClass('active');
		}
	});
	// header-search
	$(document).on('click','.header-search-link',function(e){
		e.preventDefault();
		if($(this).hasClass('active')){
			$('.header').removeClass('search-vis');
			$('.header-search').removeClass('vis');
			$('.header-search-link').removeClass('active');
		}else{
			$('.header').addClass('search-vis');
			$('.header-search').addClass('vis');
			$('.header-search-link').addClass('active');
		}
	});
	$(document).mouseup(function(e){
		var container = $(".header-search");
		if (!container.is(e.target) && container.has(e.target).length === 0)
		{
			$('.header-search').removeClass('vis');
			$('.header-search-link').removeClass('active');
		}
	});

	// faq dropdown
	$(document).on('click','.faq-top',function(e){
		e.preventDefault();
		toggleFaqText( $(this) );
	});

	// select styled
	$('.styled').selectmenu({
		position: {
			my: "left top", // default
			at: "left bottom", // default
			// "flip" will show the menu opposite side if there
			// is not enough available space
			collision: "flip"  // default is ""
		}
	});

	// tumbler
	$(document).on('click','.tumbler-left',function(e){
		e.preventDefault();
		$(this).addClass('active');
		$('.tumbler-right').removeClass('active');
		$(this).parent().removeClass('tumbler-reverse');
	});
	$(document).on('click','.tumbler-right',function(e){
		e.preventDefault();
		$(this).addClass('active');
		$('.tumbler-left').removeClass('active');
		$(this).parent().addClass('tumbler-reverse');
	});

	// slider
	$(".prod-photo").on("init", function(event, slick){
		$(".prod-top .prod-current").text(parseInt(slick.currentSlide + 1));
		$(".prod-top .prod-all").text(parseInt(slick.slideCount));
	});
	$(".prod-photo").on("afterChange", function(event, slick, currentSlide){
		$(".prod-top .prod-current").text(parseInt(slick.currentSlide + 1));
		$(".prod-top .prod-all").text(parseInt(slick.slideCount));
	});
	$('.prod-photo').slick({
		dots: false,
		arrows: true,
		autoplay: false,
		prevArrow: $('.prod-top .prod-prev'),
		nextArrow: $('.prod-top .prod-next'),
		speed: 1000,
		slidesToShow: 1,
		slidesToScroll: 1,
		infinite: false
	});
	$('.brand-slider').slick({
		dots: false,
		arrows: false,
		autoplay: false,
		speed: 500,
		slidesToShow: 8,
		slidesToScroll: 8,
		responsive: [{
			breakpoint: 1023,
			settings: {
				slidesToShow: 6,
				slidesToScroll: 2,
			}
		},{
			breakpoint: 767,
			settings: {
				slidesToShow: 4,
				slidesToScroll: 2,
			}
		},{
			breakpoint: 576,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 2,
			}
		}]
	});
	$('.similar-posts-slider').slick({
		dots: false,
		arrows: true,
		autoplay: false,
		infinite: false,
		prevArrow: $('.similar-posts .prod-prev'),
		nextArrow: $('.similar-posts .prod-next'),
		speed: 500,
		slidesToShow: 4,
		slidesToScroll: 4,
		responsive: [{
			breakpoint: 1023,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
			}
		},{
			breakpoint: 767,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
			}
		},{
			breakpoint: 576,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
			}
		}]
	});
	$('.author-posts-slider').slick({
		dots: false,
		arrows: true,
		autoplay: false,
		infinite: false,
		prevArrow: $('.author-posts .prod-prev'),
		nextArrow: $('.author-posts .prod-next'),
		speed: 500,
		slidesToShow: 4,
		slidesToScroll: 4,
		responsive: [{
			breakpoint: 1023,
			settings: {
				slidesToShow: 3,
				slidesToScroll: 3,
			}
		},{
			breakpoint: 767,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
			}
		},{
			breakpoint: 576,
			settings: {
				slidesToShow: 2,
				slidesToScroll: 2,
			}
		}]
	});
	$('.blog-slider').slick({
		slidesToShow: 3,
		slidesToScroll: 3,
		arrows: true,
		prevArrow: $('.blog-slideshow .prod-prev'),
		nextArrow: $('.blog-slideshow .prod-next'),
		swipeToSlide: true,
		infinite: false
	});

	// sub mob dropdown
	$(document).on('click','.sub-mob',function(e){
		e.preventDefault();
		if($(this).hasClass('active')){
			$('.sub-info').slideUp();
			$('.sub-mob').removeClass('active');
		}else{
			$('.sub-info').slideUp();
			$('.sub-mob').removeClass('active');
			$(this).parent().find('.sub-info').slideDown();
			$(this).addClass('active');
		}
	});

	// tab setup
	$('.tab-content').addClass('clearfix').not(':first').hide();
	$('ul.tabs').each(function(){
		var current = $(this).find('li.active');
		if(current.length < 1) { $(this).find('li:first').addClass('active'); }
		current = $(this).find('li.active a').attr('href');
		$(current).show();
	});
	// tab click
	$(document).on('click', 'ul.tabs a[href^="#"]', function(e){
		e.preventDefault();
		var tabs = $(this).parents('ul.tabs').find('li');
		var tab_next = $(this).attr('href');
		var tab_current = tabs.filter('.active').find('a').attr('href');
		$(tab_current).hide();
		tabs.removeClass('active');
		$(this).parent().addClass('active');
		tabs.find('a').removeClass('active');
		$(this).addClass('active');
		$(tab_next).show();
		return false;
	});

	$('.block-link').click(function(e){
		e.preventDefault();
	});

    // general logic of ajax form submit (supports files)
    $('form.general-ajax-submit').submit(async function(e){
        e.preventDefault();
        let form = $(this);
        let button = $(this).find('button[type=submit]');
        let formData = new FormData(this);
        if (button.hasClass('cursor-wait')) {
            return;
        }

        if (form.hasClass('with-recaptcha')) {
            // grecaptcha.ready(function() {});
            let token = await grecaptcha.execute(window.Laravel.recaptcha_key, {action: 'submit'});
            formData.append('g-recaptcha-response', token);
        }

        if (form.hasClass('show-full-loader')) {
            fullLoader();
        }

        if (form.hasClass('ask')) {
            swal.fire({
                title: form.data('asktitle'),
                text: form.data('asktext'),
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: form.data('askyes'),
                cancelButtonText: form.data('askno')
            }).then((result) => {
                if (result.value) {
                    ajaxSubmit(form, formData, button);
                }
            });
            return;
        }

        ajaxSubmit(form, formData, button);
    })

    // show uploaded file name
    $('.show-uploaded-file-name input').change(function () {
        let name = $(this).val().split('\\').pop();
        $(this).closest('.show-uploaded-file-name').find('.custom-file-label').text(name);
    })

    // show uploaded file preview
    $('.show-uploaded-file-preview input').change(function () {
        const [file] = this.files;
        if (file) {
            let el = $(this).closest('.show-uploaded-file-preview').find('.custom-file-preview');
            el.removeClass('d-none');
            let url = URL.createObjectURL(file);

            el.is('img')
                ? el.attr('src', url)
                : el.css('background-image', `url(${url})`);
        }
    })
    $('.show-uploaded-file-preview input').click(function(e) {
        e.stopPropagation();
    })

    let flash = $('[flash-notif-data]').data('flash');
    if (flash) {
        if (flash.level == 'success') {
            showToast(flash.message);
        } else {
            showToast(flash.message, false);
        }
    }

    // remove author in mailer page
    $('.remove-author-btn').click(function(e) {
        e.preventDefault();
        $('input[name=filters\\[author\\]]').val('');
        $('input[name=author_name]').val('');
    });

    // submit import creat form when import file uploaded
    $('#import-file').change(function(e) {
        e.preventDefault();
        console.log('trigger submit'); //! LOG
        $(this).closest('form').trigger('submit');
    })

    // trigger element by click manuly
    $('[data-trigger]').click(function() {
        $($(this).data('trigger')).trigger('click');
    })

    // shoa all imported posts
    $('.see-import-posts').click(function(e) {
        e.preventDefault();

        let button = $(this);

        if (button.hasClass('cursor-wait')) {
            return;
        }

        button.addClass('cursor-wait');

        $.ajax({
            url: button.attr('href'),
            type: 'get',
            success: (response)=>{
                button.removeClass('cursor-wait');
                swal.fire({html: response.data});
            },
            error: function(response) {
                button.removeClass('cursor-wait');
                showServerError(response);
            }
        });
    })

    // unfold faq requested FAQ paragraph
    let params = (new URL(document.location)).searchParams;
    let faqSlug = params.get("question");
    if (faqSlug){
        toggleFaqText($('.faq-item #'+faqSlug));
    }

	// import rules page - show cateogies in popup
	$('.show-all-categories-in-popup').click(function(e) {
		e.preventDefault();
		let html = $('.all-categories-as-popup');
		swal.fire({
			title: 'Test',
			html: html
		});
	})

    //show modal contacts
    $('.show-contacts').click(function(){
        var button = $(this);
        button.addClass('loading');
        $.ajax({
            type: "get",
            url: button.data('url'),
            success: function(response) {
                button.removeClass('loading');
                let emails = response.data.emails.join(', ');
                let phones = response.data.phones.join(', ');
                swal.fire({
                    html: `<p>${trans('ui_email')}: <b>${emails}</b></p>
                        <p>${trans('ui_phone')}: <b>${phones}</b></p>`,
                    showConfirmButton: false,
                    showCancelButton: true,
                });
            },
            error: function(response) {
                button.removeClass('loading');
                showToast(response.responseJSON.message, false);
            }
        });
    });

    // send message
    $('.send-message').click(function(){
        let url = $(this).data('url');
        let name = $(this).data('user');
        swal.fire({
            title: trans('ui_sendMessagePopupTitle') + name,
            input: 'textarea',
            confirmButtonText: trans('ui_sendMessagePopupSendBtn'),
            showConfirmButton: true,
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: (message) => {
                let response = $.ajax({
                    async: false,
                    url,
                    type: 'post',
                    data: {
                        message,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                });

                if (response.status == 422 || !response.responseJSON.success) {
                    Swal.showValidationMessage(response.responseJSON.message);
                    return false;
                }

                return response.responseJSON;
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: result.value.message,
                    showConfirmButton: true,
                    confirmButtonText: trans('ui_sendMessagePopupGotToChat'),
                    showCancelButton: true,
                }).then((result2) => {
                    if (result2.isConfirmed) {
                        window.location.href = result.value.data.chat_url;
                    }
                })
            }
        });
    });

    // send message to self alert
    $('.send-message-to-self').click(function(){
        showToast(trans('messages_canNotChatToSelf'), false);
    });

    //count views
    let postviewurl = $('[page-data]').data('viewurl');
    if (postviewurl) {
        $.ajax({
            type: "POST",
            url: postviewurl,
            data: {
                _method: 'PUT',
                _token: $('meta[name=csrf-token]').attr('content')
            },
        });
    }

    // load more notification in profile section
    $(document).on('click', '.load-more-notifications', function (e) {
        e.preventDefault();
        let button = $(this);
        let waitClass = 'cursor-wait';
        if (button.hasClass(waitClass)) {
            return;
        }
        let url = button.attr('href');
        button.addClass(waitClass);
        $.ajax({
            url,
            success: (response)=>{
                button.closest('tr').remove();
                $('.notifications-table').append(response.data.html);
                button.removeClass(waitClass);
            },
            error: function(response) {
                showServerError(response);
                button.removeClass(waitClass);
            }
        });
    })

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
});

function trans(key) {
    return window.Laravel.translations[key];
}

async function ajaxSubmit(form, formData, button, successCallback=null) {
    $('.form-error').empty();
    button.addClass('cursor-wait');
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method'),
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: (response)=>{
            if (successCallback) {
                successCallback(response);
                return;
            } else {
                fullLoader(false);
                button.removeClass('cursor-wait');
                showServerSuccess(response);
            }
        },
        error: function(response) {
            fullLoader(false);
            button.removeClass('cursor-wait');
            showServerError(response);
        }
    });
}

// toast notification object
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

// show message depends on role and fade out it after 3 sec
function showToast(title, icon=true) {
	Toast.fire({
        icon: icon ? 'success' : 'error',
        title: title
    });
}

// show popup notification
function showPopUp(title=null, text=null, role) {
    if (title===null) {
        title = role ? 'Success' : 'Oops!';
    }
    if (text===null) {
        text = role ? '' : 'Something went wrong!';
    }
    swal.fire(title, text, role ? 'success' : 'error');
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

// show loading overlay
function fullLoader(show=true) {
    show ? $('.full-loader').removeClass('d-none') : $('.full-loader').addClass('d-none');
}

// general error logic, after ajax form submit been processed
function showServerError(response) {
    fullLoader(false);

    if (response.status == 422) {
        swal.close();
        let r = response.responseJSON ?? JSON.parse(response.responseText)
        let firstError = null;
        for ([field, value] of Object.entries(r.errors)) {
            let dotI = field.indexOf('.');
            // if (dotI != -1) {
            //     field = field.slice(0, dotI);
            // }
            field = field.replaceAll('.', '\\.');
            let errorText = '';
            let errorElement = $(`.form-error[data-input=${field}]`);
            errorElement = errorElement.length ? errorElement : $(`.form-error[data-input="${field}[]"]`);
            errorElement = errorElement.length ? errorElement : $(`[name=${field}]`).closest('.form-group').find('.form-error');
            errorElement = errorElement.length ? errorElement : $(`[name="${field}[]"]`).closest('.form-group').find('.form-error');

            if (!errorElement.length) {
                continue;
            }

            for (const [key, error] of Object.entries(value)) {
                errorText = errorText ? errorText+'<br>'+error : error;
            }
            errorElement.html(errorText);

            if (!firstError || errorElement.offset().top < firstError.offset().top) {
                firstError = errorElement;
            }
        }

        let firstErrorField = firstError.data('input').replaceAll('.', '\\.');
        let input = $(`[name=${firstErrorField}]`);

        if (input.length && !isScrolledIntoView(input)) {
            animatedScroll(input, 50);
        }
    } else {
        let msg = response.responseJSON?.message;
        msg = msg ? msg : response.statusText;
        showPopUp(null, msg ? msg : null, false);
    }
}

// check is element in user`s viewport
function isScrolledIntoView(elem) {
    var docViewTop = $(window).scrollTop();
    var docViewBottom = docViewTop + $(window).height();

    var elemTop = $(elem).offset().top;
    var elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

// general success logic, after ajax form submit been processed
function showServerSuccess(response) {
    if (response.success) {
        if (response.data?.redirect) {
            window.location.href = response.data.redirect;
        } else if (response.message) {
            showToast(response.message);
        }
    } else {
        showPopUp(null, response.message, false);
    }
}

// smooth scroll animation
function animatedScroll(el, more=0) {
    if (!el) {
        return;
    }

    $('html, body').animate({
        scrollTop: el.offset().top - 100 - more
    }, 200);
}

// faq dropdown
function toggleFaqText(item) {
    console.log(`toggle el`, item); //! LOG
	if(item.hasClass('active')){
		$('.faq-hidden').slideUp();
		$('.faq-top').removeClass('active');
		$('.faq-item').removeClass('active');
	}else{
		$('.faq-hidden').slideUp();
		$('.faq-top').removeClass('active');
		$('.faq-item').removeClass('active');
		item.parent().addClass('active');
		item.parent().find('.faq-hidden').slideDown();
		item.addClass('active');
	}
}

//user adds post to favourites
$(document).on('click', '.add-to-fav', function (e) {
    e.preventDefault();
    let button = $(this);
    if (button.hasClass('loading')) {
        return;
    }
    let url = button.attr('href');
    button.addClass('loading')
    $.ajax({
        url: url,
        type: 'post',
        data: {
            _method: 'PUT',
            _token: $('meta[name=csrf-token]').attr('content')
        },
        success: (response)=>{
            showToast(response.message);
            button.toggleClass('active');
            button.removeClass('loading');
        },
        error: function(response) {
            button.removeClass('loading');
            showToast(response.responseJSON.message, false);

        }
    });
})
