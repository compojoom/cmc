/**
 * Created by DanielDimitrov on 09.09.13.
 */
window.addEvent('domready', function(){
	var fields = $$('li.cmc-newsletter').filter(function(item, index) {
		if(!item.getElement('input#cmc_newsletter')) {
			return item;
		}
	});
	fields.setStyle('display', 'none');
	fields.removeProperty('required');
	document.id('cmc_newsletter').addEvent('click', function() {
		if(this.checked)
		{
			fields.setStyle('display', 'block');
			$$('.cmc_req').addClass('required').set('required', 'required');
		}
		else
		{
			fields.setStyle('display', 'none');
			$$('.cmc_req').removeClass('required').removeProperty('required');
		}
	});
});