/**
 * @author Yves Hoppe- compojoom.com
 * @date: 10.09.2013
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

jQuery(document).ready(function () {
	var $ = jQuery,
		fieldsets = [],
		display = 'block',
		labels = $('label.cmc-label');

	labels.closest('fieldset').each(function (key, el) {
		if (!$(el).find('input.cmc-checkbox-subscribe').length) {
			fieldsets.push($(el));
		}
	});

	if (!fieldsets.length) {
		// Try to find out if we are dealing with k2
		var elements = labels.closest('tr');
		elements.each(function (key, el) {
			if (!jQuery(el).find('input.cmc-checkbox-subscribe').length) {
				fieldsets.push($(el));
			}
		});

		elements.prev('tr:not(:first)').each(function (key, el) {
			if (!jQuery(el).find('input.cmc-checkbox-subscribe').length) {
				fieldsets.push($(el));
			}
		});

		if (fieldsets.length) {
			display = 'table-row';
		}
	}

	fieldsets.each(function (el) {
		el.css('display', 'none');
		el.find('input').prop('disabled', 'disabled');
	});

	$('#jform_cmc_newsletter').on('click', function () {
		if ($(this).prop('checked')) {
			fieldsets.each(function (el) {
				el.css('display', display);
				el.find('input').removeProp('disabled');
			});
		}
		else {
			fieldsets.each(function (el) {
				el.css('display', 'none');
				el.find('input').prop('disabled', 'disabled');
			});
		}
	});
});