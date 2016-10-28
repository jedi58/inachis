var InachisPostEdit = {
	_pageTitle: '',
	_postOrPage: 'post',
	categoryList: '',

	_init: function()
	{
		var currentContentType = window.location.href.match(/(post|page)/gi);
		if (null !== currentContentType) {
			this._postOrPage = currentContentType[0].toLowerCase();
		}
		this.initTooltips();
		this.initTitleChange();
	},

	initTooltips: function()
	{
		$('[data-qtip-content]').qtip({
			content: {
				text: function() {
					return $(this).attr('data-tip-content');
				},
				title: function() {
					return $(this).attr('data-tip-title');
				}
			},
			position: {
				target: 'mouse',
				adjust: { mouse: true }
			}
		});
	},

	initTitleChange: function()
	{
		$(document).on('keyup', '#post__edit #title, #post__edit #subTitle', $.proxy(function(event)
		{
			if ([
				13, // enter
				16, // shift
				17, // ctrl
				18, // alt
				19, // pause
				20, // capslock
				27, // esc
				33, // page up
				34, // page down
				35, // end
				36, // home
				37, // left
				38, // up
				39, // right
				40, // down
				45, // insert
				91, // left-window / apple
				92, // right-window / apple
				93, // select key
				112, // f1
				113, // f2
				114, // f3
				115, // f4
				116, // f5
				117, // f6
				118, // f7
				119, // f8
				120, // f9
				121, // f10
				122, // f11
				123, // f12
				124, // f13
				125, // f14
				126, // f15
				127, // f16
				128, // f17
				129, // f18
				130, // f19
				144, // num lock
				145 // scroll lock
			].indexOf(event.which) >= 0) {
				return;
			}
			var urlInput = $('input#url');
			urlInput.val(this.getUrlFromTitle());
		}, this));
	},

	getUrlFromTitle: function()
	{
		var $postContainer = $('#post__edit'),
			title = this.urlify($postContainer.find('#title').val()),
			subTitle = this.urlify($postContainer.find('#subTitle').val());
		if (title.length > 0 && subTitle.length > 0) {
			title += '-';
		}
		title += subTitle;
		if (this._postOrPage === 'post') {
			title = $('#postDate').val().substring(0,10) + '/' + title.substring(0, 255);
		}
		// @todo XHR to check if URL is already in use
		return title;
	},

	urlify: function(value)
	{
		if (typeof value === 'undefined') {
			return;
		}
		return value.toLowerCase().replace(/[_\s]/g, '-').replace(/[^a-z0-9\-]/gi, '')
	}
};

$(document).ready(function () {
	InachisPostEdit._init();
});
