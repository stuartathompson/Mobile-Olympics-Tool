jQuery(document).ready(function($){
$('.posttype-add').click(function(){
	var content = '<tr valign="top">'
        + '<th scope="row">New Option Name</th>'
        + '<td><input type="text" name="new_option_name" value="" /></td><td><input type="button" class="posttype-remove button" value="Remove" /></td>'
        + '</tr>';
	$('.form-table').append(content);
	return false;
})

$('.posttype-remove').click(function(){
	$(this).parents('tr').remove();
})
});