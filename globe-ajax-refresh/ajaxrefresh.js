jQuery(document).ready(function($) {
	
	var postIds = '';
	$('#refresh').click(function(){
		$(this).addClass('refreshing');
		$.ajax({
			url:ajaxurl.ajaxurl,
			type:'POST',
			dataType:'html',
			cache: false,
			data:{
				'action':'ajax_refresh_loop'
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
	
	function ajaxCheckNewPosts(){
	// Determine the latest post ID, ignoring stickies
	var postId = 0;
	$('#loop article').each(function(){
		if(parseInt($(this).attr('id').split('-')[1]) > postId) postId = $(this).attr('id').split('-')[1];	
	});
	$.ajax({
		url:ajaxurl.ajaxurl,
		type:'POST',
		dataType:'html',
		cache: false,
		data:{
			'action':'ajax_check_new_posts',
			'postId':postId
		},
		success: function(response){
			if(response){
				if(response > 0){
					postIds = response;
					var responseCount = response.split(',').length;
					document.title = '(' + responseCount + ')' + document.title;
					$('#new-alert-number').text(responseCount);
					if(parseInt(responseCount) > 1){
						$('#new-alert-text').text("new updates");
					} else {
						$('#new-alert-text').text("new update");
					}
					$('#new-alert-container').slideDown();
				}
			} else {
		}
		}
	});
	}

	var ajaxRefreshT = setInterval(function(){
		ajaxCheckNewPosts();
	},20000);
	
	// Load new posts on click
	$('#new-alert-container a').click(function(){
		$.ajax({
			url:ajaxurl.ajaxurl,
			type:'POST',
			dataType:'html',
			cache: false,
			data:{
				'action':'ajax_refresh_loop',
				'postIds':postIds
			},
			success: function(response){
				if(response){
					$('#loop-wrapper').prepend(response);
					document.title = document.title.split(')')[1];
					$.each(postIds.split(','),function(i,d){
						$('#post-' + d).css('background-color','#fffeef');
						setTimeout(function(){
							$('#post-' + d).css('background-color','white');
						},1000);
					});
					$('.refreshing').removeClass('refreshing');
					$('body,html').animate({
						'scrollTop':0
					});
					$('#new-alert-container').slideUp(function(){
						$('#new-alert-text').text('new update');
						$('#new-alert-number').text('0');
					});
				} else {
				}
			}
		});
		return false;
	})
});
