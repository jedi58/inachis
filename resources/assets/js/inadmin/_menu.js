var InachisNavMenu = {
	_navNewVisible: false,
	_navUserVisible: false,

	_init: function()
	{
		// Add menu
		$('.admin__add-content').click($.proxy(function() {
			$('.admin__nav-new').toggle();
			this._navNewVisible = !this._navNewVisible;
			Inachis._log('New menu visible: ' + this._navNewVisible);
			if (this._navNewVisible) {
				$(document).mouseup($.proxy(this.newNavMouseOut, this));
			}
			return false;
		}, this));
		// settings menu
		$('a[href*=admin__nav-settings]').click(function() {
			$('#admin__nav-settings').toggle();
		});
		// collapse/expand links
		$('.admin__nav-expand a, .admin__nav-collapse a').click(function () {
			$('.admin__container').toggleClass('admin__container--collapsed admin__container--expanded');
		});
		// menu link (mobile only)
		$('.admin__nav-main__link a').click($.proxy(function() {
			$('.admin__container').toggleClass('admin__container--collapsed admin__container--expanded');
			$('.admin__nav-main__list').toggle('slide');
			this._navNewVisible = !this._navNewVisible;
			Inachis._log('New menu visible: ' + this._navNewVisible);
			if (this._navNewVisible) {
				$(document).mouseup($.proxy(this.newNavMouseOut, this));
			}
			return false;
		}, this));
		// user menu
		$('.admin__user > a').click($.proxy(function() {
			$('#admin__user__options').toggle();
			this._navUserVisible = !this._navUserVisible;
			Inachis._log('User menu visible: ' + this._navUserVisible);
			if (this._navUserVisible) {
				$(document).mouseup($.proxy(this.userNavMouseOut, this));
			}
			return false;
		}, this));
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
	},

	userNavMouseOut: function(e)
	{
		var container = $('#admin__user__options');
		if (!container.is(e.target) && container.has(e.target).length === 0)  {
			container.hide();
			this._navUserVisible = !this._navUserVisible;
			Inachis._log('User menu visible: ' + this._navUserVisible);
			$(document).unbind('mouseup', InachisNavMenu.userNavMouseOut);
		}
	}
};

$(document).ready(function () {
	InachisNavMenu._init();
});
