(function ($) {
    'use strict';

    $.pocketlists_pro = {
        inited: false,
        defaults: {
            debug: false,
        },
        options: {},
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
                    $pocketWrapper.find('[data-pl2pro-pocket-sidebar-labels] [data-pl2pro-label]').hide();

                    for (var labelId in r.data) {
                        var $labelFilter = $pocketWrapper.find('[data-pl2pro-pocket-sidebar-labels] [data-pl2pro-label="' + labelId + '"]');

                        $labelFilter.show()
                            .find('span[data-pl2pro-label-count]').text(r.data[labelId] === 100 ? '99+' : r.data[labelId]);
                    }
                }
            }, 'json');
        },
        log: function(message) {
            this.options.debug && console.log('pl2pro', message);
        },
        init: function (o) {
            if (this.inited) {
                return;
            }

            this.options = $.extend({}, this.defaults, o);
            $.store && !$.storage && ($.storage = new $.store());

            $(document)
                .on('click', '.pl-item-wrapper[data-id] .pl-label[data-pl2pro-label]', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var $this = $(this);

                    if ($('#pl-item-details-form').length) {
                        return;
                    }

                    if ($this.closest('[data-pl-item-add]').length) {
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

            this.inited = true;
        },
        pl2EventsHandlers: {
            item_add: function (e, data) {
                var $wrapper = data.add_wrapper.find('[data-pl2pro-wrapper]');
                $.pocketlists_pro.log({ 'item_add': $wrapper });

                $wrapper.removeData('pl2pro-item-add');

                // init($wrapper);
                $(document).trigger('itemAdd.pl2pro', {wrapper: $wrapper});

            },
            open_new_item_wrapper: function (e, data) {
                var $wrapper = data.add_wrapper.find('[data-pl2pro-wrapper]');
                $.pocketlists_pro.log({ 'open_new_item_wrapper': $wrapper });
                $wrapper.removeData('pl2pro-item-add');

                // init($wrapper);
                $wrapper.trigger('itemAdd.pl2pro', {wrapper: $wrapper});
            },
            itemDetailsOpened: function (e, data) {
                var $wrapper = data.details_wrapper.find('[data-pl2pro-wrapper]');
                $.pocketlists_pro.log({ 'itemDetailsOpened': $wrapper });
                $wrapper.removeData('pl2pro-item-add');

                // init($wrapper);
                $(document).trigger('itemAdd.pl2pro', {wrapper: $wrapper});
            },
            itemDetailsClosed: function (e, data) {
                var $wrapper = data.details_wrapper.find('[data-pl2pro-wrapper]');
                $.pocketlists_pro.log({ 'itemDetailsClosed': $wrapper });
                $wrapper.removeData('pl2pro-item-add');

                // init($wrapper);
                $(document).trigger('itemAdd.pl2pro', {wrapper: $wrapper});
            }
        }
    }
}(jQuery));
