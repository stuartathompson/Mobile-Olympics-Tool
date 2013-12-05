jQuery(document).ready(function($) {
	
	// Declare variables
	var postIds = '',
		responseHtml = '',
		checkTime = 5000;

	// Manually refresh using button
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

	// Check for new posts
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
			console.log(response);
			if(response != ''){
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
	});
	}
	
	// Regularly trigger check for new posts on home
	if($('body').hasClass('home')){
		var ajaxRefreshT = setInterval(function(){
			ajaxCheckNewPosts();
		},checkTime);
	}
	
	// Load new posts on click
	$('#new-alert-container a').click(function(){
		if(responseHtml){
			//$('#loop-wrapper').prepend(responseHtml);
			$('.ajaxposts-new').show();
			$('.ajaxposts-new article').css('background-color','#fffeef');
			setTimeout(function(){
					$('.ajaxposts-new article').css('background-color','white');
			},1000);
			document.title = document.title.replace(/^\([0-9]*\)/,'');
	/*
$.each(postIds.split(','),function(i,d){
				$('#post-' + d).css('background-color','#fffeef');
				setTimeout(function(){
					$('#post-' + d).css('background-color','white');
				},1000);
			});
*/
			$('.refreshing').removeClass('refreshing');
			$('body,html').animate({
				'scrollTop':0
			});
			$('#new-alert-container').slideUp(function(){
				$('#new-alert-text').text('new update');
				$('#new-alert-number').text('0');
			});
		}
		/*
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
				console.log(response);
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
*/
		return false;
	})
});
