$(document).ready(function() {
	// https://select2.github.io/examples.html
	$('.js-select').each(function ()
	{
		$properties = {

		};
		if ($(this).attr('data-tags')) {
			$properties.tags = 'true';
			$properties.tokenSeparators = [ ',' ];
		}
		if ($(this).attr('data-url')) {
			$properties.ajax = {
				url: $(this).attr('data-url'),
				dataType: 'json',
				data: function (params)
				{
					return {
						q: params.term, // search term
						page: params.page
					};
				},
				processResults: function (data, params)
				{
					// parse the results into the format expected by Select2
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data, except to indicate that infinite
					// scrolling can be used
					params.page = params.page || 1;

					return {
						results: data.items,
						pagination: {
							more: (params.page * 30) < data.total_count
						}
					};
				},
				cache: true
			};
		}
		$(this).select2($properties);
	});

	// https://github.com/daredevel/jquery-tree
	$('ui-tree').each(function()
	{
		
	});

	// http://xdsoft.net/jqplugins/datetimepicker/
	if ($('html').attr('lang')) {
		$.datetimepicker.setLocale($('html').attr('lang'));
	}
	$('#publishDate').each(function ()
	{
		$(this).datetimepicker({
			format: 'Y/m/d H:i',
			validateOnBlue: false
		});
	});

	$('.ui-switch').each(function ()
	{
		$properties = {
			checked: this.checked,
			clear: true
		};
		if ($(this).attr('data-label-on')) {
			$properties.on_label = $(this).attr('data-label-on');
		}
		if ($(this).attr('data-label-off')) {
			$properties.off_label = $(this).attr('data-label-off');
		}
		$(this).switchButton($properties);
	});


	$('.ui-tabbed').tabs();
});
