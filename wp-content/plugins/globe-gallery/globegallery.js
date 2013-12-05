$('document').ready(function(){

var show = 0;
$('.gi-gallery-label').click(function(){
	$('.gi-gallery-image').eq(show-1).click();
})
$('.gi-gallery-image,.gi-gallery-image a').click(function(){
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
	console.log($showImage.find('img').data('imageSrc'));
	$showImage.addClass('showing').show().find('img').attr('src',$showImage.find('img').data('imageSrc'));
	
	return false;
});

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