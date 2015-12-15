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
        scrollToTop: function () {
            if ($('body').scrollTop() > 80) {
                $('html,body').animate({scrollTop: '80px'});
            }
        },
        init: function () {
            $.pocketlists_routing.init();

            var self = this;
            $('#wa-app').on('click', '[data-pl-scroll-to-top] a', function () {
                self.scrollToTop();
            });
            self.updateAppCounter();
        }
    };
}(jQuery));