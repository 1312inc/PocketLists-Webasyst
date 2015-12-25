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
        highlightSidebar: function($li) {
            var $all_li = $('#pl-sidebar-core').find('li');
            if ($li) {
                $li.addClass('selected');
            } else {
                var hash = $.pocketlists_routing.getHash(),
                    $a = $all_li.find('a[href^="' + hash + '"]');

                if (hash) {
                    $all_li.removeClass('selected');
                }
                if ($a.length) {
                    $a.closest('li').addClass('selected');
                } else { // more complex hash
                    hash = hash.split("/");
                    if (hash[1]) {
                        $all_li.find('a[href^="' + hash[0] + '/' + hash[1] + '"]').closest('li').addClass('selected');;
                    }
                }
            }
        },
        $loading: $('<i class="icon16 loading">'),
        init: function () {
            $.pocketlists_routing.init();

            var self = this;
            self.highlightSidebar();

            $('#wa-app').on('click', '[data-pl-scroll-to-top] a', function () {
                self.scrollToTop(0, 80);
            });
            $('#pl-sidebar-core').on('click', 'a', function() {
                self.highlightSidebar($(this).closest('li'));
            });
        }
    };
}(jQuery));