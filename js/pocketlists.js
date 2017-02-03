(function ($) {
    'use strict';

    $.pocketlists = {
        $loading: $('<i class="icon16 loading">'),
        defaults: {
            isAdmin: false
        },
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
                    $a = self.$core_sidebar.find('a[href="' + hash + '"]');

                if (hash) {
                    $all_li.removeClass('selected');
                }
                if ($a.length) { // first find full match
                    $a.closest('li').addClass('selected');
                } else { // more complex hash
                    hash = hash.split("/");
                    if (hash[1]) {
                        self.$core_sidebar.find('a[href^="' + hash[0] + '/' + hash[1] + '"]').first().closest('li').addClass('selected');
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

            return;
            // DEPRECATED FEB 2017. MUST USE SMART BOTTOM/TOP SCROLL FIXED MODE.

            var $list = $('#pl-list-content');
            if ($list.length) {
                var list_top_offset = $list.offset().top,
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
                        bottom: Math.max(0, 16 - _viewport_bottom_offset),
                        right: 16
                    });
                } else {
                    $el.removeClass('sticky').css('right', 0);
                }
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
        reloadSidebar: function () {
            var self = this;

            $.get("?module=backend&action=sidebar", function (result) {
                $('#pl-sidebar-core').html(result);
                self.highlightSidebar();
                self.sortLists();
            });
        },
        sortLists: function() {
            var self = this;
            if (!self.options.isAdmin) {
                return;
            }

            var $lists_wrapper = self.$core_sidebar.find('[data-pl-sidebar-block="lists"]'),
                $team_wrapper = self.$core_sidebar.find('[data-pl-sidebar-block="team"]');

            $('[data-pl-list-id]', $lists_wrapper).droppable({
                accept: '[data-parent-id]',
                disabled: false,
                greedy: true,
                tolerance: 'pointer',
                classes: {
                    'ui-droppable': 'pl-droppable'
                },
                over: function( event, ui ) {
                    $(this).addClass('highlighted-background');
                },
                out: function( event, ui ) {
                    $(this).removeClass('highlighted-background');
                },
                drop: function(event, ui) {
                    var $item = ui.draggable,
                        $list = $(event.target),
                        list_id = $list.data('pl-list-id');

                    $item.trigger('moveToList.pl2', {id: list_id, drop: this});
                    $(this).removeClass('highlighted-background');
                    $item.addClass('pl-dropped');
                }
            });
            $('[data-pl-team-id]', $team_wrapper).droppable({
                accept: '[data-parent-id]',
                disabled: false,
                greedy: true,
                tolerance: 'pointer',
                classes: {
                    'ui-droppable': 'pl-droppable'
                },
                over: function( event, ui ) {
                    $(this).addClass('highlighted-background');
                },
                out: function( event, ui ) {
                    $(this).removeClass('highlighted-background');
                },
                drop: function(event, ui) {
                    var $item = ui.draggable,
                        $list = $(event.target),
                        team_id = $list.data('pl-team-id');

                    $item.trigger('assignTo.pl2', {id: team_id, drop: this});
                    $(this).removeClass('highlighted-background');
                    $item.addClass('pl-dropped');
                }
            });

            self.$core_sidebar.on('dropActionDone.pl2', '[data-pl-list-id], [data-pl-team-id]', function (e, data) {
                var $this = $(this);
                if (data.result) {
                    $this.addClass('pl-drop-success');
                } else {
                    $this.addClass('pl-drop-fail');
                }
                setTimeout(function () {
                    $this.removeClass('pl-drop-success pl-drop-fail');
                    // hell
                    if (data.result) {
                        setTimeout(function () {
                            $.pocketlists.reloadSidebar();
                        }, 1000);
                    }
                }, 500);

            });

            $lists_wrapper.sortable({
                item: '[data-pl-list-id]',
                placeholder: 'pl-list-placeholder',
                opacity: 0.75,
                distance: 5,
                appendTo: 'body',
                tolerance: 'pointer',
                classes: {
                    'ui-sortable-helper': 'shadowed'
                },
                start: function(e, ui ){
                    ui.placeholder.height(ui.helper.outerHeight());
                },
                stop: function (event, ui) {
                    var getLists = function () {
                        var data = [];
                        $lists_wrapper.find('[data-pl-list-id]').each(function (i) {
                            var $this = $(this);
                                // color = $this.attr('class').match(/pl-(.*)/);
                            data.push({
                                id: $this.data('pl-list-id'),
                                sort: i
                                // color: color[1]
                            });
                        });
                        return data;
                    };

                    var updateSort = function () {
                        $.post(
                            '?module=list&action=sort',
                            {
                                data: getLists()
                            },
                            function (r) {
                                if (r.status === 'ok') {
                                } else {
                                    alert(r.errors);
                                }
                            },
                            'json'
                        );
                    };

                    updateSort();
                }
            });
        },
        init: function (o) {
            $.pocketlists_routing.init();

            var self = this;
            self.$app_menu_pocket = $('#wa-app-pocketlists');
            self.$core_sidebar = $('#pl-sidebar-core');
            self.options = $.extend({}, self.defaults, o);

            // self.highlightSidebar();
            // self.sortLists();

            $('#wa-app').on('click', '[data-pl-scroll-to-top] a', function () {
                self.scrollToTop(0, 80);
            });
        }
    };
}(jQuery));
