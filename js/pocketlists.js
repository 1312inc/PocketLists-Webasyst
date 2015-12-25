(function ($) {
    'use strict';

    $.pocketlists = {
        updateAppCounter: function (count) {
            var setIcon = function (count) {
                count = parseInt(count, 10) || '';
                var counter = $('#wa-app-pocketlists').find('.indicator');
                if (!counter.length) {
                    $('#wa-app-pocketlists').find('a').append('<span class="indicator" style="display:none;"></span>');
                    counter = $('#wa-app-pocketlists').find('.indicator');
                }
                counter.text(count);
                if (count) {
                    counter.show();
                } else {
                    counter.hide();
                }
            };

            if (count) {
                setIcon(count);
            } else {
                $.get('?module=json&action=appCount', function (r) {
                    if (r.status === 'ok') {
                        setIcon(r.data);
                    }
                }, 'json');
            }
        },
        scrollToTop: function (speed, offset) {
            if ($('body').scrollTop() > offset) {
                $('html,body').animate({scrollTop: offset + 'px'}, speed);
            }
        },
        setSelectSidebar: function($li) {
            var $all_li = $('#pl-sidebar-core').find('li').removeClass('selected');
            if ($li) {
                $li.addClass('selected');
            } else {
                $all_li.find('a[href^="' + $.pocketlists_routing.getHash() + '"]').closest('li').addClass('selected');
            }
        },
        $loading: $('<i class="icon16 loading">'),
        init: function () {
            $.pocketlists_routing.init();

            var self = this;
            $('#wa-app').on('click', '[data-pl-scroll-to-top] a', function () {
                self.scrollToTop(0, 80);
            });
            $('#pl-sidebar-core').on('click', 'a', function() {
                self.setSelectSidebar($(this).closest('li'));
            });
        }
    };
}(jQuery));