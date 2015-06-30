/*
 * CMC Popup Template; v20150630
 * https://compojoom.com
 * Copyright (c) 2013 - 2015 Yves Hoppe - compojoom.com;
 */
(function ($) {
	var version = "20150630";

	$.fn.cmcpopup = function (options) {

		var settings = $.extend({
			// Default settings - see API instructions
			start: 200
		}, options);

		var holder = $.extend({
			module: null,
			cmc_fade: null,
			cmc_signup: null,
			cmc_form: null,
			btn_subscribe: null,
			btn_close: null,
			opened: false
		});

		var API = $.extend({
			init: function () {
				var as = API.readCookie("cmcpopup");

				if (as) {
					// Don't show popup
					return;
				}

				holder.cmc_fade = $(".cmc-fade");
				holder.btn_close = $(".cmc-popup-close", holder.module);

				API.initClose();

				if (settings.start == 0) {
					API.showPopup();
					holder.opened = true;
				} else {
					$(window).scroll(function(){
						var pos = $(window).scrollTop();

						if (pos >= settings.start) {
							API.showPopup();
							$(this).off('scroll');
						}
					})
				}

				return true;
			},

			initClose: function() {
				holder.cmc_fade.click(function(){
					API.hidePopup();
				});

				holder.btn_close.click(function(){
					API.hidePopup();
				});
			},

			hidePopup: function() {
				holder.cmc_fade.hide(100);
				holder.module.hide(100);

				// Set Cookie
				API.createCookie("cmcpopup", true, 7);
			},

			showPopup: function() {
				holder.cmc_fade.show(200);
				holder.module.show(200);
			},

			createCookie: function(name, value, days) {
				var expires;

				if (days) {
					var date = new Date();
					date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
					expires = "; expires=" + date.toGMTString();
				} else {
					expires = "";
				}

				document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
			},

			readCookie: function(name) {
				var nameEQ = encodeURIComponent(name) + "=";
				var ca = document.cookie.split(';');
				for (var i = 0; i < ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0) === ' ') c = c.substring(1, c.length);
					if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
				}
				return null;
			}
		});

		return this.each(function () {
			holder.module = $(this);
			var success = API.init();
		});
	}

}(jQuery));
