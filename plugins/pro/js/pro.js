(function ($) {
    'use strict';

    $.storage = new $.store();
    $.pocketlists_pro = {
        updatePocketLabelCounts: function () {
            var $pocketWrapper = $('[data-pl2-pocket-wrapper]');

            if (!$pocketWrapper.length) {
                return;
            }

            $.get('?plugin=pro&module=label&action=countByPocket', {
                pocket_id: $pocketWrapper.data('pl2-pocket-wrapper')
            }, function (r) {
                if (r.status === 'ok') {
                    $pocketWrapper.find('[data-pl2pro-pocket-sidebar-labels] span[data-pl2pro-label-count]').text(0);

                    for (var labelId in r.data) {
                        $pocketWrapper
                            .find('[data-pl2pro-pocket-sidebar-labels] [data-pl2pro-label="' + labelId + '"] span[data-pl2pro-label-count]')
                            .text(r.data[labelId] === 100 ? '99+' : r.data[labelId]);
                    }
                }
            }, 'json');
        },
        init: function () {
            $(document)
                .on('click', '.pl-item-wrapper[data-id] .pl-label[data-pl2pro-label]', function (e) {
                    e.preventDefault();

                    if ($('#pl-item-details-form').length) {
                        return;
                    }

                    $(document).trigger(
                        'openItemDetails.pl2',
                        {
                            pledit: $(this).closest('[data-id]').find('.pl-edit').get(0)
                        }
                    );
                })
                .on(
                    'item_add.pl2 item_update.pl2 item_complete.pl2',
                    this.updatePocketLabelCounts
                )
            ;
        }
    }
}(jQuery));
