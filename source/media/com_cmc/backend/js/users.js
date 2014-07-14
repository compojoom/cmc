var cmcUsers = function(){
	var $ = jQuery;

    var initialize = function() {
        var groups = $('#groups'), form = $('#addGroup');

	    groups.css('display', 'block').addClass('animated bounceIn');

	    form.on('submit', function() {
	        var usergroups = form.find('input[type=checkbox]:checked');
	        if (usergroups.length) {
		        return true;
	        } else {
		        alert('Select group please');
				return false;
	        }
        });

        $('#close').on('click', function () {
            groups.css('display', 'none').removeClass('animated bounceIn');
        });
    };

	initialize();
};