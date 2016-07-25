var InachisDashboard = {

	_init: function()
	{
		var tabs = $('.widget-posts__tabs a').click($.proxy(function (e) {
			Inachis._log('Posts tab: ' + e.target.dataset.target);
		}, this));
	}
};

$(document).ready(function () {
	InachisDashboard._init();
});
