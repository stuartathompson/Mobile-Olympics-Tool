jQuery(document).ready(function($) {
	
	// Declare variables
	var postIds = '',
		responseHtml = '',
		checkTime = 5000;

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
				responseHtml = response;
				$('#loop-wrapper').prepend('<div class="ajaxposts-new">' + responseHtml + '</div>');
				var responseCount = $('.ajaxposts-new').eq(0).find('article').length;
				// If there is at least one "article" in response
				if(responseCount > 0){
					// Add number of new articles to the current post count
					$('#new-alert-number').text((parseInt(($('#new-alert-number').text()))+(+parseInt(responseCount))));
						document.title = '(' + $('#new-alert-number').text() + ') ' + document.title.replace(/^\([0-9]*\)/,'');
						var totalResponses = $('.ajaxposts-new article').length;
						if(totalResponses > 1){
							$('#new-alert-text').text("new updates");
							$('#new-alert-container').slideDown();
						} else {
							$('#new-alert-text').text("new update");
						}
						$('#new-alert-container').slideDown();
				} else {
					// Remove only latest container, which would be empty
					$('.ajaxposts-new').eq(0).remove();
				}
			}
		}
	});
	}
	
	// Regularly trigger check for new posts on home
	if($('body').hasClass('home')){
		var ajaxRefreshT = setInterval(function(){
			console.log('checking');
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
						$(this).parent().removeClass('ajaxposts-new');
					},{'offset':'60%'});
					initWaypoint = true;
				}
			});
			$('.ajaxposts-new article').hover(function(){
				$(this).addClass('seen');
				$(this).parent().removeClass('ajaxposts-new');
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
