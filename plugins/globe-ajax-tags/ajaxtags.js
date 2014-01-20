jQuery('document').ready(function($){

	// All variables and arrays
	var curFilters = [],
		touch = false;
	
	// All selectors
	var $filterSel = $('#filterSelect'),
		$filtersCont = $('#topics'),
		$head = $('#filters'),
		$filtersItem = $('#topics span'),
		$sections = $('article'),
		filterCookie = $.cookie('globe-ajaxtags_cookie');
	/* - Mobile and other devices - */
	//check if this is a touch device
    if ($("html").hasClass('touch') || $("body").hasClass('touch')) {
        touch = true;
    }
    // Load any tags in cookies
    if(typeof filterCookie != 'undefined'){
	    $.each(filterCookie.split(','),function(i,cookie){
    		curFilters.push(cookie);
	    });
	}
    // Set any tags loaded on start — if not already in curFilters (important for tag pages)
    $filtersItem.each(function(i,d){
		if(jQuery.inArray($(d).attr('data-filter'),curFilters)==-1) curFilters.push($(d).attr('data-filter'));
    });
    
    // Add big moments to filter array if selected
    if($('#home-highlights a').hasClass('selected') && jQuery.inArray('big-moments',curFilters) == -1) curFilters.push('big-moments');
    
	// SVG fallback
	// toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script#update
	if (!Modernizr.svg) {
		var imgs = document.getElementsByTagName('img');
		var dotSVG = /.*\.svg$/;
		for (var i = 0; i != imgs.length; ++i) {
			if(imgs[i].src.match(dotSVG)) {
				imgs[i].src = imgs[i].src.slice(0, -3) + "png";
			}
		}
	}

	//bind filters select
	$filterSel.on("change", function( event ) {
		var filter = this.value;
		addFilters(filter,filter);
	});

	//bind tag item btn, except on single article pages
	if(!$('body').hasClass('single') && !$('body').hasClass('search')){
	$('body').on("click", ".tags .tag", function(event){
		if (!touch) {
//			$(this).tooltip('hide');
		}
		var filter = $(this).data('filter'),
			filterLabel = $(this).text();
		addFilters(filter,filterLabel);
		return false;
	});
	}

	if (!touch) {
/*
		$sections.tooltip({
			selector: '.tags .tag',
			delay: 100,
			trigger: 'hover',
			placement: 'top',
			title: 'Add tag to filters'
		});			
*/
	}
	// Apply filter
	function initFilters(){
		$('#ajaxtags-loader').show();
		$('body,html').animate({
			scrollTop:0
		});
		var query = '',
			action = 'ajax_tags_loop';
		// Clean filter of empty values
		curFilters = $.grep(curFilters,function(n){ return(n) });
		// If checking big-moments with at least one other tag, use the highlights query in ajaxtags.php
		if(jQuery.inArray('big-moments',curFilters) > -1 && curFilters.length > 1) action = 'ajax_tags_loop_highlights';
		$.each(curFilters,function(i,d){
			query += d.trim();
			if(i!=curFilters.length-1) query += ',';
		});
		if(curFilters.length == 0) query = '';
		$.ajax({
			url:ajaxTagUrl.ajaxurl,
			type:'POST',
			dataType:'html',
			cache: false,
			data:{
				'action':action,
				'query':query,
				'ajaxTagNonce':ajaxTagUrl.ajaxTagNonce
			},
			success: function(response){
				if(response){
					$('#filters-error').slideUp();
					$('#loop-wrapper').empty();
					$('#loop article').remove();
					$('#loop-wrapper').append(response);
					window.displayAds( true );
					// Re-add tooltip
					if($('body').hasClass('desktop')){
						$('.tags .tag').tooltip({
							title:'Add tag to filters'
						});
						$('.star').tooltip({
							placement:'top'
						});
					}
				} else {
					$('#filters-error').slideDown();
				}
				$('#ajaxtags-loader').hide();
			}
		});
	}
	function initFiltersHome(){
		var query = '';
		$.each(curFilters,function(i,d){
			query+=d.trim();
			if(i!=curFilters.length-1) query+= ',';
		});
		window.location.href = window.location.href + '?tags=' + query;
	}
	//set current filters and apply filters
	function addFilters(tag,label) {
		if (tag != "") {
			//if this filter isn't currently active, enable it
	    	if (jQuery.inArray(tag, curFilters) == -1) {
			  	//add this filter to current filters array
			  	curFilters.push(tag);
				//add filter item element
				if(label!='big moments') $filtersCont.append('<span class="item noselect" data-filter="'+tag+'">'+label+'</span>');
				//refresh waypoints and set header height (in case adding filters changes height of header)
				refreshHeader();
				//add class to mobile tag
				$('.ajaxtags-option:contains("' + label + '")').addClass('ajaxtags-on');
				//disable this filter in dropdown
				$filterSel.find('option[value="' + label + '"]').prop("disabled", true);
				//set dropdown to first option
				$filterSel.find('option')[0].selected = true;				
				//init choose filters
				initFilters();		
				// Save cookie
				$.cookie('globe-ajaxtags_cookie',curFilters.toString().replace(/^,/,''));
								
	    	} else {
	    		//alert('This filter tag is already in use.');
	    	}
	    	if($('#topics span').length > 0) $('#topics').slideDown();
        	//track filter usage
        	//interactiveTracking("Interactive - 20130914 - Financial Crash - Filter - "+tag+"");	    	
		}
	}
	//remove filter
	function removeFilter(filter,label) {
		//remove this item from current filters array
		var index = jQuery.inArray(filter, curFilters);
		curFilters.splice(index, 1);
		//remove disabled state from select dropdown
		$filterSel.find('option[value="' + filter + '"]').prop("disabled", false);
		//remove class from mobile sidebar
		$('.ajaxtags-option:contains("' + label + '")').removeClass('ajaxtags-on');
		//refresh waypoints and set header height (in case adding filters changes height of header)
		refreshHeader();		
		//re-filter items
		initFilters();
		// save cookie
		$.cookie('globe-ajaxtags_cookie',curFilters.toString());
				
		//set filter count (mobile only)
/*		if (mobile) {
			var numFilters = curFilters.length;
			if (numFilters > 0) {
				if (numFilters == 1) {
					var append = 'filter'
				} else {
					var append = 'filters'
				}
				$filterCount.fadeIn(200).html(numFilters+' '+append);
			} else if (numFilters == 0) {
				$filtersCont.delay(200).fadeOut(200);
				$filterCount.delay(200).fadeOut(200);
			}
		}
*/
		// Hide topics if none or if only remaining is big-moments
		if(curFilters.length < 1 || (curFilters.length == 1 && jQuery.inArray('big-moments',curFilters)>-1)) $('#topics').slideUp();
	}
	
	$('#ajaxtags-clear-tags').click(function(){
		curFilters = [];
		if($('#home-highlights a').hasClass('selected')) curFilters = ['big-moments'];
		$('#filters-error').slideUp();
		$filtersCont.slideUp();
		$filtersCont.find('span').fadeOut(150).remove();
		$filterSel.find('option').prop('disabled',false);
		$.cookie('globe-ajaxtags_cookie',curFilters.toString());
		refreshHeader();
		initFilters();
		return false;
	})

	//bind filter item btn

	$filtersCont.on("click", "span", function(event){
		var filter = $(this).data('filter'),
			label = $(this).text();
		$(this).fadeOut(150, function() {
			removeFilter(filter,label);
		});
	});
		

	/* - Misc -  */
	//refresh waypoints and set header height (in case adding filters changes height of header)
	function refreshHeader() {
		headHeight = $head.outerHeight();
		$head.parents('div .sticky-wrapper').css( "height", headHeight );
	//	$.waypoints('refresh');			
	}
		
	/* - Mobile menu - */
	// Mobile menu
	$('#mobile-header-menu').click(function(){
		if(!$(this).hasClass('active')){
			// Show menu
			$('#mobile-menu').show();
			// Fix body width
			$('.hide-overflow').css({
				'height':window.innerHeight
			}).addClass('active')
			$('.ajaxtags-container').css({
				'height':window.innerHeight-parseInt($('.ajaxtags-container h3').css('height'))
			})
			// Animate header and body
			$('.wrapper').animate({
				margin:'0 0 0 80%'
			},250);
			$('#mobile-header').animate({
				left:'80%'
			},250);
			$(this).addClass('active');
		} else {
			// Hide menu
			$('.wrapper').animate({
				margin:'0'
			},250,function(){
				// Remove fixed body width
				$('.hide-overflow').removeClass('active');
				// Hide menu
				$('#mobile-menu').hide();
			});
			$('#mobile-header').animate({
				left:'0'
			},250);
			$(this).removeClass('active');
		}
		return false;
	});
	
	// Mobile menu orientation change
	$(window).on('orientationchange',function(){
		if($('.hide-overflow').hasClass('active')){
			// Fix body width
			$('.ajaxtags-container').css({
				'height':window.innerHeight-parseInt($('.ajaxtags-container h3').css('height'))
			})
			// Animate header and body
			$('.wrapper').animate({
				margin:'0 0 0 80%'
			},250);
			$('#mobile-header').animate({
				left:'80%'
			},250);
			$('.hide-overflow').css({
				'height':window.innerHeight
			});
		}
	})
	
	$('.ajaxtags-option').click(function(){
		if($(this).hasClass('ajaxtags-on')){
			removeFilter($(this).text(),$(this).text());
			$filtersCont.find('span[data-filter="' + $(this).text() + '"]').fadeOut();
		} else {
			var filter = $(this).text();
			addFilters(filter,filter);
		}
	});
	
	// Apply any existing tags from cookie
    if(typeof filterCookie != 'undefined'){
    	/*
$.each(filterCookie.split(','),function(i,cookie){
    		addFilters(cookie,cookie);
    	})
*/
    }
    
    // Toggle menu selection
    var highlightsTag = 'big-moments';
    $('#filters-bar h3 a').click(function(){
    	if($(this).text().toLowerCase() != '2014 winter olympics'){
	  	  	if($('#home-highlights a').hasClass('selected')){
    			removeFilter(highlightsTag,highlightsTag);
				$filtersCont.find('span[data-filter="'+ highlightsTag +'"]').fadeOut().remove();
	    	} else {
    			var filter = $(this).text();
				addFilters(filter.replace(/ /g,'-') ,filter);
	    	}
    		$('#filters-bar h3 a').toggleClass('selected');
    	return false;
    	}
    });

});