(function ($) {
    'use strict';

    $.storage = new $.store();
    $.pocketlists_pro = {
        init: function () {
            $(document).on('click', '.pl-label[data-pl2pro-label]', function (e) {
                e.preventDefault();

                $(document).trigger(
                    'openItemDetails.pl2',
                    {
                        pledit: $(this).closest('[data-id]').find('.pl-edit').get(0)
                    }
                );
            })
        }
    }
}(jQuery));
