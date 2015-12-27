(function ($) {
    'use strict';

    $.pocketlists = {
        updateAppCounter: function (count) {
            var self = this;

            var setIcon = function (count) {
                count = parseInt(count, 10) || '';
                var counter = self.$app_menu_pocket.find('.indicator');
                var sidebar_todo_counter = self.$core_sidebar.find('[data-pl-sidebar="todo"] .count');
                if (!counter.length) {
                    self.$app_menu_pocket.find('a').append('<span class="indicator" style="display:none;">');
                    counter = self.$app_menu_pocket.find('.indicator');
                }
                if (!sidebar_todo_counter.length) {
                    sidebar_todo_counter = $('<span class="count indicator red" style="display:none;">');
                    self.$core_sidebar.find('[data-pl-sidebar="todo"]').prepend(sidebar_todo_counter);
                }
                counter.add(sidebar_todo_counter).text(count);
                if (count) {
                    counter.add(sidebar_todo_counter).show();
                } else {
                    counter.add(sidebar_todo_counter).hide();
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
            var self = this;

            var $all_li = self.$core_sidebar.find('li');
            if ($li) {
                $all_li.removeClass('selected');
                $li.addClass('selected');
            } else {
                var hash = $.pocketlists_routing.getHash(),
                    $a = $all_li.find('a[href="' + hash + '"]');

                if (hash) {
                    $all_li.removeClass('selected');
                }
                if ($a.length) { // first find full match
                    $a.closest('li').addClass('selected');
                } else { // more complex hash
                    hash = hash.split("/");
                    if (hash[1]) {
                        $all_li.find('a[href^="' + hash[0] + '/' + hash[1] + '"]').first().closest('li').addClass('selected');
                    }
                }
            }
        },
        $loading: $('<i class="icon16 loading">'),
        init: function () {
            $.pocketlists_routing.init();

            var self = this;
            self.$app_menu_pocket = $('#wa-app-pocketlists');
            self.$core_sidebar = $('#pl-sidebar-core');

            self.highlightSidebar();

            $('#wa-app').on('click', '[data-pl-scroll-to-top] a', function () {
                self.scrollToTop(0, 80);
            });
            self.$core_sidebar.on('click', 'a', function() {
                self.highlightSidebar($(this).closest('li'));
            });
        }
    };
}(jQuery));