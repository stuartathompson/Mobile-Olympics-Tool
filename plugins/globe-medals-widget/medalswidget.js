jQuery('document').ready(function(){
function showSmallerVersion(){
	$('.reutersOlympicsWidget tr.medalRow').each(function(i,d){
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
var t = setInterval(function(){
	if($('.reutersOlympicsWidget tr.medalRow').length > 0){
		showSmallerVersion();
		$('.globe-medals-table').show();
		clearInterval(t);
	}
},100);
$('body,html').on('click','.elongation',function(){
	if($('.resultsSubview2').hasClass('constrainHeight')){
		showSmallerVersion();
	} else {
		$('.reutersOlympicsWidget tr.medalRow').show();
	}
	return false;
})
$('.globe-medals-nav').click(function(){
	if($(this).hasClass('selected')){
		$('.globe-medals-plusminus').text('+ Show full medal count');
		$('.reutersOlympicsWidget,.medalCountWidget').slideUp(function(){
			showSmallerVersion();
		});
	} else {
		$('.reutersOlympicsWidget,.medalCountWidget').slideDown();
		$('.globe-medals-plusminus').text('- Show full medal count');
	}
	$(this).toggleClass('selected');
})
});