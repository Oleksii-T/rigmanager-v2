$(document).ready(function () {
	
	// scroll header
	$(window).scroll( function(){
		if ($(this).scrollTop() > 50){
			$('.header').addClass('fixed');
			$('#pop-up-container').addClass('scrolled');
		}else{
			$('.header').removeClass('fixed');
			$('#pop-up-container').removeClass('scrolled');
		}
	});
	if ($(window).scrollTop() > 50){
		$('.header').addClass('fixed');
		$('#pop-up-container').addClass('scrolled');
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
	// fancybox touch fix
	$("[data-fancybox]").fancybox({ touch: false });

	// formate phone field
	$('.format-phone').focusin(function(){
		var newVal = phoneFormater( $(this).val(), false );
		$(this).val(newVal);
	});
	$('.format-phone').focusout(function(){
		var newVal = phoneFormater( $(this).val(), false );
		var newVal = phoneFormater( newVal, true );
		$(this).val(newVal);
	});
	function phoneFormater(phone, mode) {
		if (phone) {
			if (mode) {
				for (let i = phone.length-1; i >= 0; i--) {
					if (i==1) {
						phone = phone.slice(0, i) + ' (' + phone.slice(i);
					} else if (i==3) {
						phone = phone.slice(0, i) + ') ' + phone.slice(i);
					}
					else if (i==8 || i==6) {
						phone = phone.slice(0, i) + ' ' + phone.slice(i);
					}
				}
				return phone;
			} else {
				return phone.replace(/[^0-9]+/g,"").substring(0,10);
			}
		}
	};

	//format-cost
	$("input.format-cost").focusin(function(){
		newVal = $(this).val()
			? CurrencyToNumber($(this).val())
			: '';
		$(this).val(newVal);
	});
	$("input.format-cost").focusout(function(){
		if ($(this).val()){
			var currency = $('.currency-switch.active').text();
			$(this).val( NumberToCurrency( currency, $(this).val() ) );
		}
	});
	function CurrencyToNumber(str){
		return str.replace(/[^0-9.]+/g,"");
	}
	function NumberToCurrency(currency, string) {
		res = CurrencyToNumber(string);
		res = res.replace(/^0*/g, '');
		if (!res || res[0] == '.') {
			return null;
		}
		if ( res.includes('.') ) {
			var firstDot = res.indexOf('.');
			var dots = (res.match(/\./g) || []).length;
			if ( dots != 1 ) {
				res = res.replace(/\./g,"");
				res = res.slice(0, firstDot) + '.' + res.slice(firstDot);
			}
			var coins = res.substring(firstDot);
			if ( coins.length > 3 ) {
				toCrop = coins.length - 3;
				res = res.substring(0, res.length-toCrop);
			} else if ( coins.length < 3 ) {
				// add coins
				toAdd = 3-coins.length;
				res = res+'0';
				if (toAdd == 2) {
					res = res+'0';
				}
			}
		} else {
			res = res + ".00";
		}
		for (let i=res.length-4, step=1; i >= 0; i--,step++) {
			if (step == 4) {
				res = res.slice(0, i+1) + ',' + res.slice(i+1);
				step = 1;
			}

		}
		currency=='UAH' ? res='₴'+res : res='$'+res;
		return res;
	}
	$('.currency-switch').click(function(){
		if ( $(".format-cost").length ) {
			var currency = $(this).hasClass('uah') ? '₴' : '$';
			$(".format-cost").each(function(i, obj) {
				oldVal = $(this).val();
				if (oldVal) {
					newVal = oldVal.replace(oldVal[0], currency);
					$(this).val( newVal );
				}
			});
		}
	});

	//select tags
	var selectedTag = new Object();
	selectedTag.first = "Other";
	selectedTag.second = "0";
	selectedTag.third = "0";
	selectedTag.id = "0";
	$('.tag-lvl-1 select').selectmenu({
		change: function (event, ui) {
			var val = $(this).find('option:selected').val(); //get tag id
			$('.tag-lvl-2 select, .tag-lvl-3 select').addClass('hidden'); //hide all previusly chosen tags
			$('select.tags_'+val).removeClass('hidden'); //show oppropriate child tag
			selectedTag.id = val;
			selectedTag.first = $(this).find('option:selected').text();
			selectedTag.second = "0";
			selectedTag.third = "0";
			// TODO - reset select
		}
	});//Облегшені бурові труби (ЛБТ)
	$('.tag-lvl-2 select').selectmenu({
		change: function (event, ui) {
			var val = $(this).find('option:selected').val(); //get tag id
			$('.tag-lvl-3 select').addClass('hidden'); //hide all previusly chosen tags
			selectedTag.id = val;
			val = val.replace('.', '\\.'); //escape dot in class name
			$('select.tags_'+val).removeClass('hidden'); //show oppropriate child tag
			selectedTag.second = $(this).find('option:selected').text();
			selectedTag.third = "0";
			// TODO - reset select
		}
	});
	$('.tag-lvl-3 select').selectmenu({
		change: function (event, ui) {
			selectedTag.id = $(this).find('option:selected').val();
			selectedTag.third = $(this).find('option:selected').text();
		}
	});
	$('.select-tag-btn').click(function(){
		var list = $('.form-category-list');
		list.empty();
		list.append('<li>'+selectedTag.first+'</li>');
		if (selectedTag.second != "0") {
			list.append('<li>'+selectedTag.second+'</li>');
			if (selectedTag.third != "0") {
				list.append('<li>'+selectedTag.third+'</li>');
			}
		}
		$('input[name=tag_encoded]').val(selectedTag.id);
		$.fancybox.close();
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
});

// show error from submit post with dropzone 
function showErrorsFromDropzone(dz, error) {
	$("#form-post button[type=submit]").removeClass('loading');
	$('.post-publishing-alert').addClass('hidden');
	// parse error messages
	if (typeof error['message'] != 'undefined' && error['message'] == "The given data was invalid.") { // if it is error from input fields
		showPopUpMassage(false, "{{ __('messages.postInputErrors') }}");
		var invalidInputErrors = error['errors'];
		$.each(invalidInputErrors, function(key, value) {
			$('.'+key+'.dz-error').text(value);
			$('input[name='+key+']').addClass('form-error');
			$('textarea[name='+key+']').addClass('form-error');
			$('select[name='+key+']').addClass('form-error');
			$('.'+key+'.dz-error').removeClass('hidden');
		});
	} else if (typeof error['message'] != 'undefined' && error['message'] != '') {// if it is custom error from post upload no check for 400 error code
		showPopUpMassage(false, error['message']);
	} else if (error == 'Request timedout after 10000 seconds') {
		window.location="{{loc_url(route('profile.posts'))}}";
	} else {
		showPopUpMassage(false, "{{__('messages.error')}}");
	}
	dz.removeAllFiles();
}

// faq dropdown
function toggleFaqText(item) {
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

//get digit from classes of DOM element (depends on prefix)
function getIdFromClasses(classes, prefix) {
	// regex special chars does not escaped in prefix!!!
	var reg = new RegExp("^"+prefix+"[0-9]+$", 'g');
	var result = '';
	classes.split(' ').every(function(string){
		result = reg.exec(string);
		if ( result != null ) {
			result = result + '';
			result = result.split('_')[1];
			return false;
		} else {
			return true;
		}
	});
	return result;
}

// fade out flash massages after 3 sec
$("div.flash").delay(4000).fadeOut(350);
//delete flash message if clicked on
$('body').on("click", ".flash", function(){
	$(this).remove();
});
// counter for unique pop-up ids
var popUpId = 1;
function getPopUpId() {
	popUpId++;
	return popUpId;
}
// show message depends on role and fade out it after 3 sec
function showPopUpMassage(role, massage) {
	var id = "pop-up-" + getPopUpId();
	if (role) {
		var src = "/icons/success.svg";
		role = role = "pop-up-success";
	} else {
		var src = "/icons/warning.svg";
		role = role = "pop-up-error";
	}
	$('#pop-up-container').append("<div class='pop-up "+role+"' id="+id+"><p><img src="+src+" alt='{{__('alt.keyword')}}'>"+massage+"</p><div class='animated-line'></div></div>");
	$("#"+id).delay(4000).fadeOut(350);
	setTimeout(function(){
		$("#"+id).remove();
	}, 4500);
}
//delete pop-up message if clicked on
$('body').on("click", ".pop-up", function(){
	$(this).remove();
});

//user adds post to favourites
function addPostToFav(button, postId, blade) {
    button.addClass('loading');
    //send Ajax reqeust to add Item to fav list of user
    $.ajax({
        type: "GET",
        url: blade['url'],
        data: { post_id: postId },
        success: function(data) {
            //if no server errors, change digit of favItemsAmount in nav bar
            //and change color of AddToFav btn img
            if ( data ) {
                //visualize removing/adding from fav list
                button.hasClass('active') 
                    ? showPopUpMassage(true, blade['removedMes'])
                    : showPopUpMassage(true, blade['addedMes']);
                button.toggleClass('active');
				if (typeof(removeFromFav) === typeof(Function)) {
					removeFromFav(postId);
				}
            //if server errors occures, pop up error massage
            } else {
                showPopUpMassage(false, blade['addErrorMes']);
            }
            //remove cursor wait
            button.removeClass('loading');
        },
        error: function() {
            //pop up error massage and remove cursor wait
            showPopUpMassage(false, blade['errorMes']);
            button.removeClass('loading');
        }
    });
};

// transform string to pure number
function CurrencyToNumber(str){
	return str.replace(/[^0-9.]+/g,"");
}
// transform pure number to readable currency
function NumberToCurrency(currency, string) {
	res = CurrencyToNumber(string);
	if ( res.includes('.') ) {
		var firstDot = res.indexOf('.');
		var dots = (res.match(/\./g) || []).length;
		if ( dots != 1 ) {
			res = res.replace(/\./g,"");
			res = res.slice(0, firstDot) + '.' + res.slice(firstDot);
		}
		var coins = res.substring(firstDot);
		if ( coins.length > 3 ) {
			toCrop = coins.length - 3;
			res = res.substring(0, res.length-toCrop);
		} else if ( coins.length < 3 ) {
			// add coins
			toAdd = 3-coins.length;
			res = res+'0';
			if (toAdd == 2) {
				res = res+'0';
			}
		}
	} else {
		res = res + ".00";
	}
	for (let i=res.length-4, step=1; i >= 0; i--,step++) {
		if (step == 4) {
			res = res.slice(0, i+1) + ',' + res.slice(i+1);
			step = 1;
		}

	}
	currency=='UAH' ? res='₴'+res : res='$'+res;
	return res;
}

