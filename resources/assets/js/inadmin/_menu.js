var InachisNavMenu = {
	_navNewVisible: false,

	_init: function()
	{
		$('.admin__add-content').click($.proxy(function() {
			$('.admin__nav-new').toggle();
			this._navNewVisible = !this._navNewVisible;
			Inachis._log('New menu visible: ' + this._navNewVisible);
			if (this._navNewVisible) {
				$(document).mouseup($.proxy(this.newNavMouseOut, this));
			}
		}, this));
		$('.admin__nav-expand a, .admin__nav-collapse a').click(function () {
			$('.admin__container').toggleClass('admin__container--collapsed admin__container--expanded');
		});
		$('.admin__nav-main__link a').click(function() {
			$('.admin__nav-main__list').toggle();
		});
	},

	newNavMouseOut: function(e)
	{
		var container = $('.admin__nav-new');
		if (!container.is(e.target) && container.has(e.target).length === 0)  {
			container.hide();
			this._navNewVisible = !this._navNewVisible;
			Inachis._log('New menu visible: ' + this._navNewVisible);
			$(document).unbind('mouseup', InachisNavMenu.newNavMouseOut);
		}
	}
};

$(document).ready(function () {
	InachisNavMenu._init();
});
