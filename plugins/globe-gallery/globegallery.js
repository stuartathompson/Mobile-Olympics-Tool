$('document').ready(function(){

var show = 0;
$('.gi-gallery-label').click(function(){
	$('.gi-gallery-image').eq(show-1).click();
})
// Iterate through gallery
if($('body').hasClass('desktop')){
	// If desktop, show each new photo as clicked
$('.gi-gallery-image,.gi-gallery-image a').click(function(){
	// Fix gallery height to avoid flicker
	$(this).parents('.gi-gallery').height($(this).parents('.gi-gallery').find('.gi-gallery-image.showing img').height()+$('.gi-gallery-nav').height());
	// Navigate
	var $parent = $(this).parents('.gi-gallery');
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