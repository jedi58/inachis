var InachisCategoryManager = {
    alreadyInitialised: false,

    _init: function()
    {
        $(document).on('click', '.category-manager__link', $.proxy(function()
        {
            this.createDialog();
        }, this));
    },

    createDialog: function()
    {
        var dialogWidth = $(window).width() * 0.75;
        if (dialogWidth < 380) {
            dialogWidth = 376;
        }
        $('<div id="dialog__categoryManager"><form class="form"></form></div>').dialog(
        {
            buttons: [
                {
                    text: 'Create Category',
                    class: 'button button--positive',
                    click: $.proxy(this.saveNewCategory, this)
                },
                {
                    text: 'Close',
                    class: 'button button--negative',
                    click: function() {
                        $(this).dialog('close');
                    }
                }
            ],
            close: function()
            {
                $(this).dialog('destroy');
                $(this).parent().remove();
                $('.fixed-bottom-bar').toggle();
            },
            draggable: false,
            modal: true,
            open: $.proxy(function()
            {
                $('.fixed-bottom-bar').toggle();
                this.addDialogContent();
                this.getCategoryTree();
            }, this),
            resizable: false,
            title: 'Categories',
            width: dialogWidth
        });
    },

    addDialogContent: function()
    {
        $('.ui-dialog-titlebar-close').addClass('material-icons').html('close');
        $('#dialog__categoryManager').find('form').prepend(
            '<p>' +
                '<label for="dialog__categoryManager__new">New category name</label>' +
                '<input id="dialog__categoryManager__new" placeholder="Enter category name…" type="text" />' +
            '</p>' +
            '<p>' +
                '<label for="dialog__categoryManager__existing">' +
                    '<input class="checkbox" id="dialog__categoryManager__existing" name="catParent[]" type="radio" value="-1" /> ' +
                    'As top-level category</label>' +
            '</p>' +
            '<p>As a sub-category of:</p>' +
            '<ol data-name="catParent[]"></ol>'
        );
    },

    saveNewCategory: function()
    {
        var $newCategory = $('#dialog__categoryManager').find('p').find('input').first(),
            $parentCategory = $('input[name="catParent\[\]"]:checked'),
            $createCategory = $('.ui-dialog-buttonpane').find('button').first();
        $createCategory.prop('disabled', true).html('Saving…');
        $.ajax(
            '/inadmin/ax/categoryManager/save',
            {
                complete: $.proxy(function()
                {
                    setTimeout($.proxy(function()
                    {
                        $createCategory.prop('disabled', false).removeClass('button--negative').html('Create Category')
                    }, $createCategory), 1200);
                }, $createCategory),
                data: {
                    'categoryName': $newCategory.val(),
                    'parentId': $parentCategory.val()
                },
                error: $.proxy(function()
                {
                    $createCategory.html('Failed to save').addClass('button--negative');
                }, $createCategory),
                method: 'POST',
                success: $.proxy(function()
                {
                    this.getCategoryTree();
                    $createCategory.html('<span class="material-icons">done</span> Saved OK');
                    $newCategory.val('');
                }, this)
            }
        );
    },

    getCategoryTree: function()
    {
        var $categoryManager = $('#dialog__categoryManager');
        $categoryManager.find('ol').load('/inadmin/ax/categoryManager/get', {
            default: null
        }, function(responseText, status) {
            var $uiDialog = $('.ui-dialog'),
                $categoryMangerTree = $categoryManager.find('ol');
            if (responseText.trim() === '') {
                var $dialogParas = $('#dialog__categoryManager').find('p');
                $dialogParas.last().toggle();
                $dialogParas.find('input').last().prop("checked", true);
            }
            if (status === 'success') {
                if (this.alreadyInitialised) {
                    $categoryMangerTree.bonsai('update');
                    $categoryMangerTree.bonsai('expandAll');
                    $uiDialog.position({ my: 'center', at: 'center', of: window });
                    return;
                }
                $categoryMangerTree.bonsai({
                    createInputs: 'radio',
                    expandAll: true
                });
                this.alreadyInitialised = true;
            }
            $uiDialog.position({ my: 'center', at: 'center', of: window });
        }, $categoryManager);
    }
};

$(document).ready(function () {
    InachisCategoryManager._init();
});
