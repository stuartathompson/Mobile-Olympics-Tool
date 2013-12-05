jQuery(document).ready(function($) {
	// Add tooltip to star icon
	if($('body').width() > 500){
		$('.star').tooltip({
			placement:'top'
		});
	}
	function userLikeCheck(){
		// Check for cookies
		var userlike_cookie = $.cookie('userlike_like');
		if(typeof userlike_cookie != 'undefined'){
			$.each(userlike_cookie.split(','),function(i,d){
				$('#post-' + d + ' .star a').addClass('liked');
			});
		}
	}
	userLikeCheck();
	// Trigger adding or subtracting votes
	$('.star a').click(function(){
		var trigger = 'userlike_removeLike',
			$obj = $(this);
		// Determine whether to add or not
		if(!$(this).hasClass('liked')){
			trigger = 'userlike_addLike';
			$(this).addClass('liked');
		} else {
			$obj.removeClass('liked');
		}
		// Save trigger and post ID
		var data = {
			action: trigger,
			query: parseInt($(this).parents('article').attr('id').split('-')[1])
		};
		// Send to userlike.php via admin-ajax.php
		$.ajax({
			url:ajaxurl.ajaxurl,
			type:'POST',
			dataType:'html',
			cache: false,
			data:data,
			success: function(response){
				if(response){
					// Process vote and title
					var titleResponse = response;
					if(titleResponse == 1) {
						titleResponse += ' like';
					} else if (titleResponse > 1){
						titleResponse += ' likes';
					}
					// Update field and tooltip
					$('.' + $obj.parents('.post').attr('id')).find('.user-like-count').text(response);
					$obj.parent().attr('title',titleResponse);
					$obj.parent().tooltip('hide').attr('data-original-title', titleResponse).tooltip('fixTitle').tooltip('show');
					// Add cookie to generic userlike_like array, delimited by commas
					var userlike_cookie = $.cookie('userlike_like');
					if(typeof userlike_cookie != 'undefined'){
						userlike_cookie = userlike_cookie.split(',');
						if(jQuery.inArray(data.query.toString(),userlike_cookie)>-1){
							userlike_cookie.splice(jQuery.inArray(data.query.toString(),userlike_cookie),1);
							$.cookie('userlike_like',userlike_cookie);
						} else {
							userlike_cookie = userlike_cookie.toString() + ',' + data.query;
							$.cookie('userlike_like',userlike_cookie);
						}
					} else {
						$.cookie('userlike_like',data.query);
					}
				} else {
					
				}
			}
		});
		return false;
	})

});
