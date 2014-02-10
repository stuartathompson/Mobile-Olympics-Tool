jQuery(document).ready(function($){
function toTitleCase(str)
{
    return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}
function addTagItem(){
	var val = $('#tagtype_sectionid #tagtype-input').val().split(',');
	$.each(val,function(i,d){
		if(d!= ''){
			d = d.trim();
			$('#tagtype-addnew').before('<div style="padding:5px 0;border-bottom:1px solid #ececec;"><label><input type="checkbox" id="' + d.replace(/ /g,'-').toLowerCase() + '" checked value="' + d.replace(/ /g,'-').toLowerCase() + '" name="tagtype-tags[]" />' + toTitleCase(d) + '<input type="radio" value="' + d.replace(/ /g,'-').toLowerCase() + '" name="tagtype-showfirst[]" style="float:right;margin-top: 3px;"/></label></div>');
			if(checkedCount == 0) $('#' + d.replace(/ /g,'-').toLowerCase()).parent().find('input[type="radio"]').prop('checked',true),checkedCount++;
		}
	})
	$('#tagtype-input').focus();
	$('#tagtype-input').val('');	
}
$('#tagtype-input').keyup(function(event){
	if(event.keyCode == 13) addTagItem(), event.preventDefault();
	event.preventDefault();
	return false;
})
$('input#tagtype-input').keydown(function(e){
	if(event.keyCode == 13) e.preventDefault();
});
$('#tagtype-button').click(function(){
	addTagItem();
	return false;
})
var checkedCount = 0;
$('#tagtype_sectionid input[type="checkbox"]').each(function(){
	if($(this).prop('checked')) checkedCount++;
})
$('#tagtype_sectionid input[type="checkbox"]').click(function(){
	if(checkedCount == 0) $(this).parent().find('input[type="radio"]').prop('checked',true);
	checkedCount++;
});

$('#tagtype_sectionid input[type="radio"]').click(function(){
	$(this).parent().find('input[type="checkbox"]').prop('checked',true);
	checkedCount++;
});
});