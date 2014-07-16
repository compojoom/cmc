var cmcSync = function(){
	var totalItems = null,
	batchSize = null,
	offset = null,
	progress = null,
	$ = jQuery,
	path = 'index.php?option=com_cmc&format=json&' + $('#cmc-indexer-token').prop('name') + '=1';

	var initialize = function () {
		jQuery('#close').on('click', function() {
			parent.closeIFrame();
		});

		$('input[type=checkbox]').on('click',function(){
			if($(this).prop('checked')) {
				$('#sync').prop('disabled', '');
			} else {
				if(!$('input[type=checkbox]:checked').length) {
					$('#sync').prop('disabled', 'disabled');
				}
			}
		});

		$('#sync').on('click', function() {
	        var lists = $('input:checked'), data = [];
			lists.each(function(key, list) {
		       data.push(list.name);
			});

			$('#sync').prop('disabled', true).addClass('disabled');
			$('input[type=checkbox]').addClass('disabled').prop('disabled', true);
			getRequest('task=sync.start&lists='+data.join(','));

		});
	};

	var getRequest = function (data) {
		return $.ajax({
			dataType: "json",
			url: path,
			method: 'get',
			data: data,
			success: handleResponse,
			error: handleFailure
		});
	};

	var handleResponse = function (json, resp) {
		try {
			if (json === null) {
				throw resp;
			}
			if (json.error) {
				throw json;
			}

			if(json.lists.length) {
				totalItems = json.lists[0].toSync;
				offset = json.offset*json.batchSize;
				updateProgress(json.header, json.message);

				if (json.offset * json.batchSize < json.lists[0].toSync) {
					getRequest('task=sync.batch');
				}
			} else {
				console.log('no lists anymore');
				offset = json.offset*json.batchSize;
				updateProgress(json.header, json.message);
			}
		} catch (error) {
			try {
				if (json.error) {
					$('#cmc-progress-header').html(json.header).addClass('cmc-error');
					$('#cmc-progress-message').html(json.message).addClass('cmc-error');
				}
			} catch (ignore) {
				if (error == '') {
					error = Joomla.JText._('COM_cmc_NO_ERROR_RETURNED');
				}
				$('#cmc-progress-header').html(Joomla.JText._('COM_cmc_AN_ERROR_HAS_OCCURRED')).addClass('cmc-error');
				$('#cmc-progress-message').html(error).addClass('cmc-error');
			}
		}
		return true;
	};

	var handleFailure = function (xhr) {
		json = (typeof xhr == 'object' && xhr.responseText) ? xhr.responseText : null;
		json = json ? JSON.decode(json, true) : null;
		if (json) {
			json = json.responseText != null ? Json.evaluate(json.responseText, true) : json;
		}
		var header = json ? json.header : Joomla.JText._('COM_CMC_AN_ERROR_HAS_OCCURRED');
		var message = json ? json.message : Joomla.JText._('COM_cmc_MESSAGE_RETURNED') + ' <br />' + json;
		$('cmc-progress-header').html(header).addClass('cmc-error');
		$('cmc-progress-message').html(message).addClass('cmc-error');
	};

	var updateProgress = function (header, message) {
		progress = (offset / totalItems) * 100;
		var pb = $('#cmc-progress-container');
		$('#cmc-progress-header').html(header);
		$('#cmc-progress-message').html(message);

		if (pb && progress < 100) {
			pb.css('width', progress + '%').parent('div').removeClass('active');
		} else if (progress >= 100) {
			pb.css('width', '100%');
		}
	};

	initialize();
};