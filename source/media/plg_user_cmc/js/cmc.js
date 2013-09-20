/**
 * @author Yves Hoppe- compojoom.com
 * @date: 10.09.2013
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

window.addEvent('domready', function(){
	var fieldsets = new Elements;
	$$('label.cmc-label ! fieldset').each(function(el) {
		if(!el.getElement('input.cmc-checkbox-subscribe')) {
			fieldsets.push(el);
		}
	});
	fieldsets.setStyle('display', 'none');
	document.id('jform_cmc_newsletter').addEvent('click', function() {
		if(this.checked)
		{
			fieldsets.setStyle('display', 'block');
			$$('input.cmc_req').addClass('required');
		}
		else
		{
			fieldsets.setStyle('display', 'none');
			$$('input.cmc_req').removeClass('required');
		}
	});
});