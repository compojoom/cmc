/**
 * Created by DanielDimitrov on 09.09.13.
 */
jQuery(document).ready(function(){
	var $ = jQuery;
	var fields = $('li.cmc-newsletter').filter(function(index, item) {
		if(!$(item).find('input#cmc_newsletter').length) {
			return $(item);
		}
	});

	fields.css('display', 'none');
	fields.removeProp('required');
	$('#cmc_newsletter').on('click', function() {
		if($(this).prop('checked'))
		{
			fields.css('display', 'block');
			$('.cmc_req').addClass('required').prop('required', 'required');
		}
		else
		{
			fields.css('display', 'none');
			$('.cmc_req').removeClass('required').removeProp('required');
		}
	});
});