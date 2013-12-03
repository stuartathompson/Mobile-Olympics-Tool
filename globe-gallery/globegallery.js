$('document').ready(function(){

var isMobile = navigator.userAgent.match(/(iPhone|iPod|iPad|Android|BlackBerry)/);

        if (isMobile) {

$('.gi-gallery-nav a').click(function(){
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

}

});