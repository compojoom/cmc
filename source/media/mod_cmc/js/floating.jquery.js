/*
 * CMC Popup Template; v20150630
 * https://compojoom.com
 * Copyright (c) 2013 - 2015 Yves Hoppe - compojoom.com;
 */
(function ($) {
	var version = "20150705";

	$.fn.cmcfloating = function (options) {

		var settings = $.extend({
			mode: 'left'
		}, options);

		var holder = $.extend({
			module: null,
			btn_float: null,
			opened: false
		});

		var API = $.extend({
			init: function () {
				holder.btn_float = $(".cmc-floating-btn-" + settings.mode);

				API.initToggle();

				return true;
			},

			initToggle: function() {
				holder.btn_float.click(function(){
					if (holder.opened) {
						API.hidePopup();
					} else {
						API.showPopup();
					}
				});
			},

			hidePopup: function() {
				if (settings.mode == 'left') {
					holder.module.animate({left: "-250px"});
					holder.btn_float.animate({left: "0"});
				} else if (settings.mode == 'right') {
					holder.module.animate({right: "-250px"});
					holder.btn_float.animate({right: "0"});
				} else {
					holder.module.animate({bottom: "-150px"});
					holder.btn_float.animate({bottom: "10px"});
				}

				holder.opened = false;
			},

			showPopup: function() {
				if (settings.mode == 'left') {
					holder.module.animate({left: 0});
					holder.btn_float.animate({left: "250px"});
				}  else if (settings.mode == 'right') {
					holder.module.animate({right: "0px"});
					holder.btn_float.animate({right: "250px"});
				} else {
					holder.module.animate({bottom: "0px"});
					holder.btn_float.animate({bottom: "160px"});
				}

				holder.opened = true;
			}
		});

		return this.each(function () {
			holder.module = $(this);
			var success = API.init();
		});
	}

}(jQuery));
