jQuery(document).ready(function($){

function addTagItem(){
	var val = $('#tagtype_sectionid #tagtype-input').val().split(',');
	$.each(val,function(i,d){
		if(d!= ''){
			d = d.trim();
			$('#tagtype-addnew').before('<div style="padding:5px 0;border-bottom:1px solid #ececec;"><label><input type="checkbox" id="' + d.replace(/ /g,'-').toLowerCase() + '" checked value="' + d.replace(/ /g,'-').toLowerCase() + '" name="tagtype-tags[]" />' + d + '</label></div>');
		}
	})
	$('#tagtype-input').val('');	
}
$('#tagtype-input').keyup(function(event){
	if(event.keyCode == 13) addTagItem();
	event.preventDefault();
})
$('#tagtype-button').click(function(){
	addTagItem();
	return false;
})
});