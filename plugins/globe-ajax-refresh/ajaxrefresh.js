jQuery(document).ready(function($) {
	
	// Declare variables
	var postIds = '',
		responseHtml = '',
		checkTime = 5000;

	// Manually refresh using button
	$('#refresh').click(function(){
		$(this).addClass('refreshing');
		$.ajax({
			url:ajaxRefreshUrl.ajaxurl,
			type:'POST',
			dataType:'html',
			cache: false,
			data:{
				'action':'ajax_refresh_loop',
				'nonce':ajaxRefreshUrl.ajaxTagNonce
			},
			success: function(response){
				if(response){
					$('#loop-wrapper').empty();
					$('#loop-wrapper').append(response);
				} else {
				}
				$('.refreshing').removeClass('refreshing');
				$('body,html').animate({
					'scrollTop':0
				});
			}
		});
	})

	// Check for new posts
	function ajaxCheckNewPosts(){
	// Determine the latest post ID, ignoring stickies
	var postId = 0;
	$('#loop article').each(function(){
		if(parseInt($(this).attr('id').split('-')[1]) > postId) postId = $(this).attr('id').split('-')[1];	
	});
	$.ajax({
		url:ajaxRefreshUrl.ajaxurl,
		type:'POST',
		dataType:'html',
		cache: false,
		data:{
			'action':'ajax_check_new_posts',
			'postId':postId,
			'nonce':ajaxRefreshUrl.ajaxTagNonce
		},
		success: function(response){
			// If a response exists
			if(response != ''){
				// If there is at least one "article" div in response
				var responseCount = $('.ajaxposts-new').eq(0).find('article').length;
				if(responseCount > 0){
					responseHtml = response;
					$('#loop-wrapper').prepend('<div class="ajaxposts-new">' + responseHtml + '</div>');
					var responseCount = $('.ajaxposts-new').eq(0).find('article').length;
					$('#new-alert-number').text((+parseInt(($('#new-alert-number').text()))+(+parseInt(responseCount))));
					document.title = '(' + $('#new-alert-number').text() + ') ' + document.title.replace(/^\([0-9]*\)/,'');
					if(parseInt(responseCount) > 1){
						$('#new-alert-text').text("new updates");
					} else {
						$('#new-alert-text').text("new update");
					}
					$('#new-alert-container').slideDown();
				}
			}
		}
	});
	}
	
	// Regularly trigger check for new posts on home
	if($('body').hasClass('home')){
		var ajaxRefreshT = setInterval(function(){
			ajaxCheckNewPosts();
		},checkTime);
	}
	
	// Show new posts on click
	$('#new-alert-container a').click(function(){
		if(responseHtml){
			$('.ajaxposts-new').show();

			/* - De-highlight posts that are in frame - */
			// Ensure user scrolls before registering their waypoint
			var initWaypoint = false;
			// Remove old window binds
			$('.ajaxposts-new article').waypoint('destroy');
			$(window).off('scroll').scroll(function(){
				// Init waypoint once
				if(!initWaypoint){
					$('.ajaxposts-new article').waypoint(function(){
						$(this).addClass('seen');
					},{'offset':'60%'});
					initWaypoint = true;
				}
			});
			$('.ajaxposts-new article').hover(function(){
				$(this).addClass('seen');
			});
			
			/* - Reset window overall - */
			document.title = document.title.replace(/^\([0-9]*\)/,'');
			$('.refreshing').removeClass('refreshing');
			$('body,html').animate({
				'scrollTop':0
			});
			$('#new-alert-container').slideUp(function(){
				$('#new-alert-text').text('new update');
				$('#new-alert-number').text('0');
			});
			
			/* - Update the latest ID cookie - */
			var lastSeenPostId = $.cookie('globeolympics-lastseenpost'),
				newLastSeenPostId = 0;
			$('#loop article').each(function(){
				thisId = parseInt($(this).attr('id').split('-')[1]);
				if(thisId > lastSeenPostId){
					if(thisId > newLastSeenPostId) newLastSeenPostId = thisId;
				}
			});
			$.cookie('globeolympics-lastseenpost',newLastSeenPostId)
		}
		return false;
	})
});
