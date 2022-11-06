$(document).ready(function () {

    let formElements = $('.selectpicker');

    formElements.each(function () {
        const formElement = $(this);
        const ajaxUrl = formElement.data('ajax-url');
        const selectPicker = formElement.selectpicker({
            liveSearch: true
        });
        if (ajaxUrl) {
            selectPicker.ajaxSelectPicker({
                ajax: {
                    url: ajaxUrl,
                    data: function () {
                        var params = {
                            q: '{{{q}}}'
                        };

                        return params;
                    }
                },
                locale: {
                    emptyTitle: 'Start typing to search...'
                },
                preserveSelected: false
            });
        }
    });

});