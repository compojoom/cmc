/**
 * @author Yves Hoppe- compojoom.com
 * @date: 10.09.2013
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

jQuery(document).ready(function(){
	var $ = jQuery;
	var fieldsets = [];
	$('label.cmc-label').closest('fieldset').each(function(key, el) {
		if(!$(el).find('input.cmc-checkbox-subscribe').length) {
			fieldsets.push($(el));
		}
	});

	fieldsets.each(function(el){
		console.log(el.find('input'));
		el.css('display', 'none');
		el.find('input').prop('disabled', 'disabled');
	});
	$('#jform_cmc_newsletter').on('click', function() {
		if($(this).prop('checked'))
		{
			fieldsets.each(function(el) {
				el.css('display', 'block');
				el.find('input').removeProp('disabled');
			});
		}
		else
		{
			fieldsets.each(function(el) {
				el.css('display', 'none');
				el.find('input').prop('disabled', 'disabled');
			});
		}
	});
});