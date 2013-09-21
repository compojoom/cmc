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
	fieldsets.each(function(el){
		var fields = el.getElements('input').set('disabled', 'disabled')
	});
	document.id('jform_cmc_newsletter').addEvent('click', function() {
		if(this.checked)
		{
			fieldsets.setStyle('display', 'block');
			fieldsets.each(function(el) {
				el.getElements('input').removeProperty('disabled');
			});
		}
		else
		{
			fieldsets.setStyle('display', 'none');
			fieldsets.each(function(el) {
				el.getElements('input').set('disabled', 'disabled');
			});
		}
	});
});