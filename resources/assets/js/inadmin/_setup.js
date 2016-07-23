var Setup = {
	initialise: function()
	{
		$('.form__setup input').on('keyup blur change', function(e) {
			var input = $(this);
			if (input.value != '' && input.checkValidity()) {
				input.addClass('input__complete');
			}
		});
	}
}
