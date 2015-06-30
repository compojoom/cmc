/*
 * CMC Popup Template; v20150630
 * https://compojoom.com
 * Copyright (c) 2013 - 2015 Yves Hoppe - compojoom.com;
 */
(function ($) {
	var version = "20150630";

	$.fn.cmcfloating = function (options) {

		var settings = $.extend({
		}, options);

		var holder = $.extend({
			module: null,
			btn_close: null,
			opened: false
		});

		var API = $.extend({
			init: function () {
				var as = API.readCookie("cmcfloating");

				if (as) {
					// Don't show it
					holder.module.hide();
					return;
				}

				holder.btn_close = $(".cmc-floating-close", holder.module);

				API.initClose();

				return true;
			},

			initClose: function() {
				holder.btn_close.click(function(){
					API.hidePopup();
				});
			},

			hidePopup: function() {
				holder.module.hide(100);

				// Set Cookie
				API.createCookie("cmcfloating", true, 7);
			},

			showPopup: function() {
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
