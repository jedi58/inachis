$(document).ready(function() {
	var $uiToggle = $('.ui-toggle');
	$uiToggle.each(function()
	{
		var targetElement = $(this).attr('data-target'),
			targetDefaultState = $(this).attr('data-target-state');
		if (targetDefaultState === 'hidden') {
			$(targetElement).hide();
		}
	});
	$uiToggle.on('click', function(event)
	{
		$($(this).attr('data-target')).toggle();
	});

	// https://select2.github.io/examples.html
	$('.js-select').each(function ()
	{
		$properties = {
			allowClear: true,
			maximumInputLength: 20
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
						q: params.term,
						page: params.page
					};
				},
				delay: 250,
				method: 'POST',
				processResults: function (data, params)
				{
					params.page = params.page || 1;
					return {
						results: data.items,
						pagination: {
							more: (params.page * 25) < data.totalCount
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
