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
        init: function () {
            $.pocketlists_routing.init();

            var self = this;
            $('#wa-app').on('click', '[data-pl-scroll-to-top] a', function () {
                self.scrollToTop(0, 80);
            });
            self.updateAppCounter();
        }
    };
}(jQuery));