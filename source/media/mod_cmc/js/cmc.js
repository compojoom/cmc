/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 09.07.12
 *
 * @copyright  Copyright (C) 2008 - 2012 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

var cmc = function(form) {
	initialize = function(form) {
		form = jQuery(form);
		form.submit(function(e) {
			e.preventDefault();
			jQuery.ajax({
				type: "POST",
				url: form.attr('action'),
				data: form.serialize() + '&ajax=true',
				beforeSend: function() {
					form.addClass('cmc-loading');
					form.find('.cmc-spinner').first().css('display', 'inline-block');
				}
			}).done(function(data) {
				var div = form.parent('div');
                if(data.error == true) {
	                div.find('.cmc-error').css('display', 'block').html(data.html);
                } else {
                    if(data.html == 'updated') {
	                    div.find('.cmc-updated').first().css('display', 'block');
                    } else {
	                    div.find('.cmc-saved').first().css('display', 'block');
                    }
                }

				form.hide(400, function() {
					jQuery('html, body').animate({
						scrollTop: div.offset().top
					}, 200);
				});
			});

			return false;
		});
	};

	// Initialize handlers and attach validation to form
	initialize(form);
};