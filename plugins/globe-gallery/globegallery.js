$('document').ready(function(){

var show = 0;
$('.gi-gallery-label').click(function(){
	$('.gi-gallery-image').eq(show-1).click();
})
// Iterate through gallery
if($('body').hasClass('desktop')){
	// If desktop, show each new photo as clicked
$('body').on('click','.gi-gallery-image,.gi-gallery-image a,.gi-gallery-nav a',function(){
	// Navigate
	var $parent = $(this).parents('.gi-gallery');
	
	// Fix gallery height to avoid flicker
	$parent.find('.gi-gallery-image img').css('height',$(this).parents('.gi-gallery').find('.gi-gallery-image.showing img').height());
	
	show = $parent.find('.gi-gallery-image.showing').index();
	
	if($(this).hasClass('prev')){
		if(show==0){
			show = $parent.find('.gi-gallery-image').length-1;
		} else {
			show--;
		}
	} else {
		if(show==$parent.find('.gi-gallery-image').length-1){
			show = 0;
		} else {
			show++;
		}
	}
	
	// Change number
	$parent.find('.gi-gallery-num span').text(show+1);
	
	// Toggle image visibility
	$parent.find('.gi-gallery-image').hide().removeClass('showing');
	var $showImage = $parent.find('.gi-gallery-image').eq(show);
	$showImage.addClass('showing').show().find('img').attr('src',$showImage.find('img').data('imageSrc'));
	
	return false;
});
} else {
	// If mobile, load all images once on click to properly trigger gallery
	var clickedOnce = false;
	$('.gi-gallery-image,.gi-gallery-image a').click(function(){
		if(!clickedOnce){
			$('.gi-gallery-image img').each(function(i,img){
				$(img).attr('src',$(img).data('imageSrc'));
			});
			clickedOnce = true;
		}
	});
}
      /*
  
        } else {

$('.gi-gallery-nav a,.gi-gallery-image').click(function(){
	var $parent = $(this).parents('.gi-gallery');
	var show = $parent.find('.gi-gallery-image.showing').index();
	if($(this).hasClass('prev')){
		if(show==0){
			show = $parent.find('.gi-gallery-image').length-1;
		} else {
			show--;
		}
	} else {
		if(show==$parent.find('.gi-gallery-image').length-1){
			show = 0;
		} else {
			show++;
		}
	}
	$parent.find('.gi-gallery-image').hide().removeClass('showing');
	$parent.find('.gi-gallery-image').eq(show).addClass('showing').show();
	
	return false;
});


}*/

});