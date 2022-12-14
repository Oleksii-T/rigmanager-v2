$(document).ready(function () {
    $('.select2').select2();

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
    $('form.general-ajax-submit').submit(function(e){
        e.preventDefault();
        let form = $(this);
        let button = $(this).find('button[type=submit]');
        let formData = new FormData(this);
        if (button.hasClass('cursor-wait')) {
            return;
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
            el.attr('src', URL.createObjectURL(file));
        }
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

});


function ajaxSubmit(form, formData, button) {
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
            fullLoader(false);
            button.removeClass('cursor-wait');
            showServerSuccess(response);
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
        for ([field, value] of Object.entries(r.errors)) {
            let dotI = field.indexOf('.');
            if (dotI != -1) {
                field = field.slice(0, dotI);
            }
            let errorText = '';
            let errorElement = $(`.form-error[data-input=${field}]`);
            errorElement = errorElement.length ? errorElement : $(`.form-error[data-input="${field}[]"]`);
            errorElement = errorElement.length ? errorElement : $(`[name=${field}]`).closest('.form-group').find('.form-error');
            errorElement = errorElement.length ? errorElement : $(`[name="${field}[]"]`).closest('.form-group').find('.form-error');
            for (const [key, error] of Object.entries(value)) {
                errorText = errorText ? errorText+'<br>'+error : error;
            }
            errorElement.html(errorText);
        }
    } else {
        let msg = response.responseJSON?.message;
        msg = msg ? msg : response.statusText;
        showPopUp(null, msg ? msg : null, false);
    }
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
$('.add-to-fav').click(function(e) {
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
