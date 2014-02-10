jQuery('document').ready(function(){
function showSmallerVersion(){
	$('.globe-medals-widget .reutersOlympicsWidget tr.medalRow').each(function(i,d){
		if(i>2 && $(d).find('.label span').text() != 'CAN') $(d).hide();
		if($(d).find('.label span').text() == 'CAN'){
			var nums = $(d).find('.number');
			$('.globe-medals-gold .globe-medals-num').text($(nums[0]).text());
			$('.globe-medals-silver .globe-medals-num').text($(nums[1]).text());
			$('.globe-medals-bronze .globe-medals-num').text($(nums[2]).text());
		}
	});
	$('.elongation').text('⊞ Show All');
	$('.subview').addClass('constrainHeight');
}
if($('.reutersOlympicsWidget').parents('.globe-medals-widget')){
var t = setInterval(function(){
	if($('.globe-medals-widget .reutersOlympicsWidget tr.medalRow').length > 0){
		showSmallerVersion();
		$('.globe-medals-table').show();
		clearInterval(t);
	}
},100);
}
$('body,html').on('click','.globe-medals-widget .elongation',function(){
	window.location.href = window.location.href.split('?')[0].split('#')[0] + '/medals';
	return false;
})
$('.globe-medals-widget .globe-medals-nav').click(function(){
	if($(this).hasClass('selected')){
		$('.globe-medals-plusminus').text('+ Show top medal counts');
		$('.globe-medals-showmore a').hide();
		$('.reutersOlympicsWidget,.medalCountWidget').hide();
		showSmallerVersion();
	} else {
		$('.reutersOlympicsWidget,.medalCountWidget').show();
		$('.globe-medals-showmore a').show();
		$('.globe-medals-plusminus').text('- Hide top medal counts');
	}
	$(this).toggleClass('selected');
})
});