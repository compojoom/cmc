var cmcSync = new Class({
	totalItems: null,
	batchSize: null,
	offset: null,
	progress: null,

	path: 'index.php?option=com_cmc&format=json',
	initialize: function () {
		this.offset = 0;
		this.progress = 0;
		this.pb = new Fx.ProgressBar(document.id('cmc-progress-container'));
		this.path = this.path + '&' + document.id('cmc-indexer-token').get('name') + '=1';

		$$('input[type=checkbox]').addEvent('click',function(){
			if(this.get('checked')) {
				document.id('sync').set('disabled', false);
				document.id('sync').removeClass('disabled');
			} else {
				if(!$$('input[type=checkbox]:checked').length) {
					document.id('sync').set('disabled', true);
					document.id('sync').addClass('disabled');
				}
			}
		});

		document.id('sync').addEvent('click', function() {
	        var lists = $$('input:checked'), data = [];
			lists.each(function(list) {
		       data.push(list.name);
			});

			document.id('sync').set('disabled', true);
			document.id('sync').addClass('disabled');
			$$('input[type=checkbox]').addClass('disabled');
			$$('input[type=checkbox]').set('disabled', true);
			this.getRequest('task=sync.start&lists='+data.join(',')).send();

		}.bind(this));
	},
	getRequest: function (data) {
		return new Request.JSON({
			url: this.path,
			method: 'get',
			data: data,
			onSuccess: this.handleResponse.bind(this),
			onFailure: this.handleFailure.bind(this)
		});
	},
	handleResponse: function (json, resp) {
		try {
			if (json === null) {
				throw resp;
			}
			if (json.error) {
				throw json;
			}

			if(json.lists.length) {
				this.totalItems = json.lists[0].toSync;
				this.offset = json.offset*json.batchSize;
				this.updateProgress(json.header, json.message);

				if (json.offset * json.batchSize < json.lists[0].toSync) {
					this.getRequest('task=sync.batch').send();
				}
			} else {
				this.offset = json.offset*json.batchSize;
				this.updateProgress(json.header, json.message);
			}
		} catch (error) {
			if (this.pb) document.id(this.pb.element).dispose();
			try {
				if (json.error) {
					document.id('cmc-progress-header').set('html', json.header).addClass('cmc-error');
					document.id('cmc-progress-message').set('html', json.message).addClass('cmc-error');
				}
			} catch (ignore) {
				if (error == '') {
					error = Joomla.JText._('COM_cmc_NO_ERROR_RETURNED');
				}
				document.id('cmc-progress-header').set('html', Joomla.JText._('COM_cmc_AN_ERROR_HAS_OCCURRED')).addClass('cmc-error');
				document.id('cmc-progress-message').set('html', error).addClass('cmc-error');
			}
		}
		return true;
	},
	handleFailure: function (xhr) {
		json = (typeof xhr == 'object' && xhr.responseText) ? xhr.responseText : null;
		json = json ? JSON.decode(json, true) : null;
		if (this.pb) document.id(this.pb.element).dispose();
		if (json) {
			json = json.responseText != null ? Json.evaluate(json.responseText, true) : json;
		}
		var header = json ? json.header : Joomla.JText._('COM_CMC_AN_ERROR_HAS_OCCURRED');
		var message = json ? json.message : Joomla.JText._('COM_cmc_MESSAGE_RETURNED') + ' <br />' + json
		document.id('cmc-progress-header').set('html', header).addClass('cmc-error');
		document.id('cmc-progress-message').set('html', message).addClass('cmc-error');
	},
	updateProgress: function (header, message) {
		this.progress = (this.offset / this.totalItems) * 100;

		document.id('cmc-progress-header').set('html', header);
		document.id('cmc-progress-message').set('html', message);
		if (this.pb && this.progress < 100) {
			this.pb.set(this.progress);
		} else if (this.pb) {
			document.id(this.pb.element).dispose();
			this.pb = false;
		}
	}
});