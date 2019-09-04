var InachisDialog = {
    alreadyInitialised: false,

    className: '',
    buttons: [],
    templateName: '',
    title: '',

    _init: function()
    {
        if ($('.dialog__link').length > 0) {
            this.title = $('.dialog__link').data('title'),
                this.templateName = $('.dialog__link').data('templateName'),
                this.className = $('.dialog__link').data('className'),
                this.buttons = JSON.parse(window.atob($('.dialog__link').data('buttons')));

            this.buttons = (typeof this.buttons != 'undefined' && this.buttons instanceof Array) ? this.buttons : [ this.buttons ];

            $(document).on('click', '.dialog__link', $.proxy(function()
            {
                this.createDialog();
            }, this));
        }
    },

    createDialog: function()
    {
        var dialogWidth = $(window).width() * 0.75;
        if (dialogWidth < 380) {
            dialogWidth = 376;
        }
        $('<div id="' + this.className + '"><form class="form"></form></div>').dialog(
            {
                buttons: this.buttons.concat([
                    {
                        text: 'Close',
                        class: 'button button--negative',
                        click: function() {
                            $(this).dialog('close');
                        }
                    }
                ]),
                close: function()
                {
                    $(this).dialog('destroy');
                    $(this).parent().remove();
                    $('.fixed-bottom-bar').toggle();
                },
                dialogClass: 'ui-dialog-loading',
                draggable: false,
                modal: true,
                open: $.proxy(function()
                {
                    $('.fixed-bottom-bar').toggle();
                    this.addDialogContent(this.templateName);
                }, this),
                resizable: false,
                title: this.title,
                width: dialogWidth
            }
        );
    },

    addDialogContent: function(templateName)
    {
        $('.ui-dialog-titlebar-close').addClass('material-icons').html('close');
        $('.ui-dialog-content').load(
            '/incc/ax/' + this.hyphenToCamel(templateName) + '/get', {
                selectedImage: ''
            }, function() // response, status, xhr
            {
                $(this).parent().removeClass('ui-dialog-loading');
                $('#dialog').dialog('option', 'position', { my: 'center', at: 'center', of: window });
            }
        );
    },

    hyphenToCamel: function(hyphenatedString)
    {
        return hyphenatedString.replace(/-([a-z])/g, function (g) { return g[1].toUpperCase(); });
    }
};

$(document).ready(function () {
    InachisDialog._init();
});
