var InachisImageManager = {
    saveUrl: '',

    _init: function()
    {
        $('.ui-dialog-secondary-bar a').click(this.toggleUploadImage);

        $('.ui-dialog-image-uploader form').submit(function (event)
        {
            var $submitButton = $('.ui-dialog-image-uploader button[type=submit]');
            $submitButton.prop('disabled', true);
            $.ajax({
                type: 'POST',
                url: $submitButton.data('submit-url'),
                data: $(this).serializeArray(),
                dataType: 'json',
                encode: true
            })
                .done(function(data) {
                    if (data['result'] === 'success') {
                        var $imageCount = $('.ui-dialog-secondary-bar strong'),
                            $gallery = $('.gallery ol'),
                            newImage = '<li>' +
                                '<label for="chosenImage_' + data['image']['id'] + '">' +
                                    '<img alt="' + data['image']['altText'] + '" src="' + data['image']['filename'] + '" />' +
                                    '<span>' + data['image']['title'] + '</span>' +
                                '</label>' +
                                '<input id="chosenImage_' + data['image']['id'] + '" name="chosenImage[]" type="radio" value="' + data['image']['id'] + '" />';
                        if (data['image']['filename'].substring(0, 4) === 'http') {
                            newImage += '<em class="material-icons">link</em>';
                        }
                        newImage += '</li>';
                        $gallery.append(newImage);
                        $imageCount.text(parseInt($imageCount.text(), 10) + 1);
                    }
                    InachisImageManager.toggleUploadImage();
                    $('.ui-dialog-image-uploader button[type=submit]').prop('disabled', false);
                });
            event.preventDefault();
        });
    },

    enableChooseButton: function()
    {

    },

    chooseImageAction: function()
    {

    },

    searchImages: function()
    {
        // queue up ajax request to get results; should previous search be cancelled if not complete?
    },

    toggleUploadImage: function()
    {
        $('.ui-dialog-image-uploader form').trigger('reset');
        $('.ui-dialog-image-uploader').toggle();
        $('.gallery').toggle();
    }
};
