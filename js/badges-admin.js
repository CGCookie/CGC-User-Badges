jQuery(document).ready(function($) {
	// Media Uploader
	var formfield = '';
	$('.cgc_ub_upload_image_button').on('click', function(e) {
		e.preventDefault();
		formfield = $('.image_src', $(this).parent());
		tb_show('', 'media-upload.php?post_id=null&TB_iframe=true');
		return false;
	});	

	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html) {
		if (formfield) {
			imgurl = $('a','<div>'+html+'</div>').attr('href');
			formfield.val(imgurl);			
			tb_remove();			  
		} else {
			window.original_send_to_editor(html);
		}			
		// Clear the formfield value so the other media library popups can work as they are meant to. - 2010-11-11.
		formfield = '';
	}
	
	$('#cgc_ub_type').change(function() {
		var method = $('option:selected', this).val();
		$('#cgc_ub_ajax').show();
		if(method == 'conditional') {
			data = {
				action: 'cgc_ub_conditionals_select'
			};
			$.post(ajaxurl, data, function(response) {
				$(response).insertAfter('#cgc_ub_method_row');
				$('#cgc_ub_ajax').hide();
			});
		} else {
			$('#cgc_ub_conditionals').remove();
			$('#cgc_ub_ajax').hide();
		}
	});
	
});