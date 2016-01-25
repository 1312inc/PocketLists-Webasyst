(function ($) {
    'use strict';

    $.pocketlists = {
        $loading: $('<i class="icon16 loading">'),
        defaults: {},
        options: {},
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
        setTitle: function(title) {
            var self = this;
            var $h1 = $('#wa-app .content h1').first();
            if ($h1.length && !title) {
                title = $h1.contents().filter(function () {
                    return this.nodeType == 3 && this.nodeValue.trim().length > 0;
                })[0].nodeValue.trim()
            }
            if (title) {
                $('title').html(title + " &mdash; " + self.options.account_name);
            }
        },
        stickyDetailsSidebar: function() {
            var list_top_offset = $('#pl-list-content').offset().top,
                _viewport_top_offset = $(window).scrollTop(),
                _window_height = $(window).height(),
                $el = $('.pl-details');

            if ($el.find('.fields form').height() > _window_height) {
                return;
            }

            if (_viewport_top_offset > list_top_offset) {
                $el.addClass('sticky');
                var _viewport_bottom_offset = $(document).height() - _window_height - _viewport_top_offset;

                $el.css({
                    bottom: Math.max(0, 16-_viewport_bottom_offset),
                    right: 16
                });
            } else {
                $el.removeClass('sticky').css('right', 0);
            }
        },
        resizeTextarea: function ($textarea) {
            if ($textarea.is(':visible')) {
                $textarea.css('height', 'auto');
                $textarea.css('height', ($textarea.get(0).scrollHeight - parseInt($textarea.css('padding-top')) - parseInt($textarea.css('padding-bottom'))) + 'px');
            }
        },
        initNotice: function(wrapper_selector) {
            var $wrapper = $(wrapper_selector);
            if (!$.storage.get('pocketlists/notice/' + wrapper_selector)) {
                $wrapper.show().one('click', '.close', function() {
                    $.storage.set('pocketlists/notice/' + wrapper_selector, 1);
                    $wrapper.slideUp();
                });
            } else {
                $wrapper.remove();
            }
        },
        init: function (o) {
            $.pocketlists_routing.init();

            var self = this;
            self.$app_menu_pocket = $('#wa-app-pocketlists');
            self.$core_sidebar = $('#pl-sidebar-core');
            self.options = $.extend({}, self.defaults, o);

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