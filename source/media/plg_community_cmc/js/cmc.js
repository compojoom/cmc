/**
 * Created by DanielDimitrov on 09.09.13.
 */
window.addEvent("domready", function () {
	document.id("cmc_newsletter").addEvent("click", function () {
		if(this.get('checked')) {
			$$(".cmc-newsletter").setStyle("display", "block");
		} else {
			$$(".cmc-newsletter").setStyle("display", "none");
		}
	})
});