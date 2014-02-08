jQuery(document).ready(function($){
	$('.apibox_column input[type="radio"]').click(function(){
		var postID = $(this).attr('name').split('-')[1],	
			apiboxValue = $(this).val(),
			action = 'apibox_columns';
		$.ajax({
			url:ajaxurl,
			type:'POST',
			dataType:'html',
			cache: false,
			data:{
				'action':action,
				'postID':postID,
				'apiboxValue':apiboxValue
			},
			success: function(response){
				if(response){
					console.log(response);
				}
			}
		});

	})
});