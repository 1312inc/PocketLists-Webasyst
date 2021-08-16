(function ($) {
    'use strict';

    $.pocketlists = {
        $loading: $('<i class="icon16 loading">'),
        $wa: null,
        defaults: {
            isAdmin: false,
            debug: false
        },
        options: {},
        reloadSidebarInAction: false,
        dropInAction: false,
        skipHighlightSidebar: false,
        scrollToContent: function() {
            this.scrollToTop(80, $('#content').offset().top);
        },
        updateAppCounter: function (count) {
            var self = this;

            var setIcon = function (count) {
                count = parseInt(count, 10) || '';
                var counter = self.$app_menu_pocket.find('.indicator');
                if (!counter.length) {
                    self.$app_menu_pocket.find('a').append('<span class="indicator" style="display:none;">');
                    counter = self.$app_menu_pocket.find('.indicator');
                }
            };

            if (count) {
                setIcon(count);
            } else {
                $.get('?module=backendJson&action=appCount', function (r) {
                    if (r.status === 'ok') {
                        setIcon(r.data);
                    }
                }, 'json');
            }
        },
        scrollToTop: function (speed, offset) {
            if ($(document).scrollTop() > offset) {
                $('html,body').animate({scrollTop: offset + 'px'}, speed);
            }
        },
        highlightSidebar: function ($li, href) {
            if (this.skipHighlightSidebar) {
                return;
            }

            var self = this;

            var $all_li = self.$core_sidebar.find('li');
            if ($li) {
                $all_li.removeClass('selected');
                $li.addClass('selected');
            } else if (href) {
                $all_li.removeClass('selected');
                $li = self.$core_sidebar.find('a[href^="' + href + '"]').first().closest('li');
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
                        while(hash.length) {
                            hash.pop();
                            var href = hash.join('/');

                            var $found_li = self.$core_sidebar.find('a[href^="' + href + '"]').first().closest('li');
                            if ($found_li.length) {
                                $found_li.addClass('selected');
                                break;
                            }
                        }
                    }
                }
            }
        },
        skipNextTitle: false,
        setTitle: function (title, skipNext) {
            var self = this;

            if (self.skipNextTitle === true) {
                self.skipNextTitle = false;
                return;
            }

            self.skipNextTitle = skipNext || false;
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
        stickyDetailsSidebar: function () {

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
                        right: 0
                    });
                } else {
                    $el.removeClass('sticky').css('right', 0);
                }
            }
        },
        resizeTextarea: function ($textarea) {
            if ($textarea.is(':visible')) {
                $textarea
                    .css('height', $textarea.data('pl2-textarea-rows') ? 'auto' : 0)
                    .css('height', $textarea.get(0).scrollHeight);
            }
        },
        initNotice: function (wrapper_selector) {
            var $wrapper = $(wrapper_selector);
            if (!$.storage.get('pocketlists/notice/' + wrapper_selector)) {
                $wrapper.show().one('click', '.close', function () {
                    $.storage.set('pocketlists/notice/' + wrapper_selector, 1);
                    $wrapper.slideUp();
                });
            } else {
                $wrapper.remove();
            }
        },
        reloadSidebar: function () {
            var self = this;

            self.reloadSidebarInAction = true;

            $.get("?module=backend&action=sidebar", function (result) {
                self.reloadSidebarInAction = false;

                if (self.dropInAction) {
                    return;
                }

                self.$core_sidebar.html(result);
                self.initCollapse();
                self.highlightSidebar();
                self.sortLists();
            });
        },

        sortLists: function () {
            var self = this;

            var $team_wrapper = self.$core_sidebar.find('[data-pl-sidebar-block="team"]'),
                $pocket_wrapper = self.$core_sidebar.find('[data-pl2-sidebar-wrapper="pockets"] ul:first');

            $('[data-pl-team-id]', $team_wrapper).droppable({
                accept: '[data-parent-id]',
                disabled: false,
                greedy: true,
                tolerance: 'pointer',
                classes: {
                    'ui-droppable': 'pl-droppable'
                },
                over: function (event, ui) {
                    self.dropInAction = true;

                    $(this).addClass('highlighted-background');
                },
                out: function (event, ui) {
                    self.dropInAction = false;

                    $(this).removeClass('highlighted-background');
                },
                drop: function (event, ui) {
                    var $item = ui.draggable,
                        $list = $(event.target),
                        team_id = $list.data('pl-team-id');

                    self.dropInAction = false;

                    $item.trigger('assignTo.pl2', {id: team_id, drop: this});
                    // $(this).removeClass('highlighted-background');
                    $item.addClass('pl-dropped');
                    $item.hide();
                }
            });

            $('[data-pl-pocket-id]', $pocket_wrapper).droppable({
                accept: '[data-pl-list-id]',
                disabled: false,
                greedy: true,
                tolerance: 'pointer',
                classes: {
                    'ui-droppable': 'pl-droppable'
                },
                over: function (event, ui) {
                    self.dropInAction = true;
                    $(this).addClass('highlighted-background');
                },
                out: function (event, ui) {
                    self.dropInAction = false;
                    $(this).removeClass('highlighted-background');
                },
                drop: function (event, ui) {
                    var $list = ui.draggable,
                        $pocket = $(event.target),
                        pocket_id = $pocket.data('pl-pocket-id');

                    self.dropInAction = false;

                    $list.trigger('moveTo.pl2', {id: pocket_id, drop: this});
                    // $(this).removeClass('highlighted-background');
                    $list.addClass('pl-dropped');
                    $list.hide();
                }
            });

            if (!self.options.isAdmin) {
                return;
            }

            $pocket_wrapper.sortable({
                draggable: '[data-pl-pocket-id]',
                delay: 200,
                delayOnTouchOnly: true,
                animation: 150,
                forceFallback: true,
                ghostClass:'pl-list-placeholder',
                // chosenClass:'album-list-chosen',
                // dragClass:'album-list-drag',
                onEnd: function(event) {
                    let $item = $(event.item);
                    /* хак для предотвращения срабатывания клика по элементу после его перетаскивания*/
                    let $link = $item.find('[onclick]'),
                        href = $link.attr('onclick');
                    $link.attr('onclick', 'javascript:void(0);');
                    setTimeout(() => $link.attr('onclick', href),500)

                    var getPockets = function () {
                        var data = [];
                        $pocket_wrapper.find('[data-pl-pocket-id]').each(function (i) {
                            var $this = $(this);
                            // color = $this.attr('class').match(/pl-(.*)/);
                            data.push({
                                id: $this.data('pl-pocket-id'),
                                sort: i
                                // color: color[1]
                            });
                        });
                        return data;
                    };

                    var updateSort = function () {
                        $.post(
                            '?module=pocket&action=sort',
                            {
                                data: getPockets()
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
        enabled_prevent_close_browser: false,
        enable_prevent_close_browser: function ($el, msg) {
            var self = this;
            self.$core_sidebar.find('a').each(function (e) {
                if (!$(this).data('pl2-onbeforeunload')) {
                    $(this).on('click.pl2', function (e) {
                        var msg = msg || $_('You are about to leave this page without saving your input. Arse you sure?');
                        if ($el) {
                            $el.data('can_blur', false);
                        }
                        if (!confirm(msg)) {
                            e.preventDefault();
                            e.stopPropagation();
                        } else {
                            self.disable_prevent_close_browser();
                        }
                    });
                }
            }).data('pl2-onbeforeunload', true);

            if (!self.enabled_prevent_close_browser) {
                self.enabled_prevent_close_browser = true;
                msg = msg || ($_('Close') + '?');
                window.onbeforeunload = function (e) {
                    if ($el) {
                        $el.data('can_blur', false);
                    }
                    return msg;
                };
            }
        },
        disable_prevent_close_browser: function () {
            var self = this;
            self.enabled_prevent_close_browser = false;
            self.$core_sidebar.find('a').off('click.pl2').removeData('pl2-onbeforeunload');
            window.onbeforeunload = function (e) {
            };
        },
        collapse: function (action) {
            var $this = $(this),
                data = $this.data('pl-collapsible'),
                $wrapper = $('[data-pl-collapsible-wrapper="' + data + '"]');

            if ($wrapper.length && action) {
                action = action === true ? ($wrapper.is(':visible') ? 'hide' : 'show') : action;
                if (action == 'show') {
                    $wrapper.show();
                    $this.addClass('darr').removeClass('rarr');
                } else if (action == 'hide') {
                    $wrapper.hide();
                    $this.addClass('rarr').removeClass('darr');
                }
                $.storage.set('pocketlists/collapsible/' + data, action);
            }
        },
        initCollapse: function () {
            var self = this;

            $('#pl-sidebar-core')
                .removeData('sidebar')
                .waShowSidebar({
                    direction: "down"
                });

            $('[data-pl-collapsible]')
                .off('click.pl2')
                .on('click.pl2', function (e) {
                    e.preventDefault();
                    self.collapse.call(this, true);
                })
                .each(function () {
                    var $this = $(this),
                        data = $this.data('pl-collapsible'),
                        action = $.storage.get('pocketlists/collapsible/' + data);

                    self.collapse.call(this, action);
                });
        },
        init: function (o) {
            var self = this;
            self.$app_menu_pocket = $('#wa-app-pocketlists');
            self.$core_sidebar = $('#pl-sidebar-core');
            self.options = $.extend({}, self.defaults, o);
            self.$wa = $('#wa');

            self.initCollapse();
            self.highlightSidebar();
            self.sortLists();

            self.$core_sidebar.on('dropActionDone.pl2', '[data-pl-team-id], [data-pl-pocket-id]', function (e, data) {
                var $this = $(this);

                $this.removeClass('highlighted-background');

                if (data.result) {
                    $this.addClass('pl-drop-success');
                } else {
                    $this.addClass('pl-drop-fail');
                    data.$obj.show();
                }

                setTimeout(function () {
                    $this.removeClass('pl-drop-success pl-drop-fail');
                }, 1000);

                // hell
                if (!self.dropInAction && data.result && !self.reloadSidebarInAction) {
                    $.pocketlists.reloadSidebar();
                }
            });

            $('#wa-app').on('click.pl2', '[data-pl-scroll-to-top] a', function () {
                self.scrollToTop(0, 80);
            });

            self.$core_sidebar.on('click.pl2', '[data-pl-action="show-all-team"]', function (e) {
                e.preventDefault();

                self.$core_sidebar.find('[data-pl-sidebar-block="team"] li').show();
                $(this).hide();
            });

            self.$core_sidebar.on('click.pl2', '.pl-tiny-ad-close', function (e) {
                e.preventDefault();

                $(this).closest('.pl-tiny-ad').hide();
                $.post('?module=backendJson&action=hideTinyAd');
            });

            $(document)
                .on('keydown', function (e) {
                    switch (true) {
                        case e.which === 18:
                            self.$wa.addClass('pl-keypress-alt');
                            break;
                        case e.which === 16:
                            self.$wa.addClass('pl-keypress-shift');
                            break;
                    }
                }).on('keyup', function (e) {
                    switch (true) {
                        case e.which === 18:
                            self.$wa.removeClass('pl-keypress-alt');
                            break;
                        case e.which === 16:
                            self.$wa.removeClass('pl-keypress-shift');
                            break;
                    }
                });

            self.windowResize();

            self.$core_sidebar.on('keydown', '[data-pl2-action="search"]', function (e) {
                var keycode = (e.keyCode ? e.keyCode : e.which);

                if(keycode == '13'){
                    var $this = $(this),
                        term = $this.val();

                    window.location.hash = '/search/' + term;
                }
            });

            $.pocketlists_routing.init({
                user_id: o.userId
            });
        },
        hasGlobalScrollbar: function () {
            return $(document).height() > $(window).height();
        },
        log: function (msg) {
            console.log('pocketlists log', msg);
        },
        debugLog: function (msg) {
            this.options.debug && console.log('pocketlists log', msg);
        },
        flexHack: function () {
            var $compensation = $('#pl-chat-waapps-height-compensation');
            if (this.hasGlobalScrollbar() && !$compensation.is(':visible')) {
                $compensation.show();
                this.log('pl-chat-waapps-height-compensation show');
            } else if (!this.hasGlobalScrollbar() && $compensation.is(':visible')) {
                $compensation.hide();
                this.log('pl-chat-waapps-height-compensation hide');
            }
        },
        windowResize: function () {
            var self = this,
                delay = 500,
                throttled = false;

            function onResize() {
                self.flexHack();
            }

            window.addEventListener('resize', function() {
                if (!throttled) {
                    onResize();
                    throttled = true;
                    setTimeout(function() {
                        throttled = false;
                    }, delay);
                }
            });

            onResize();
        },
        scrollToEl: function(el) {
            el.scrollIntoView({
                behavior: "smooth",
                block: "end",
                inline: "nearest"
            });
        },
        sendNotifications: function (appUrl) {
            appUrl = appUrl || '';
            $.post(appUrl + '?module=backendJson&action=sendNotifications', function(r) {
                if (r.status === 'ok') {
                    var sent = parseInt(r.data);
                    sent && console.log('pocketlists: notification send ' + sent);
                } else {
                    console.log('pocketlists: notification send error ' + r.error);
                }
            });
        },
        lazyItems: function (config) {
            $(window).off('scroll.pl2');

            if (config) {
                var $loading = config.$loading,//$('#pl-list-content .lazyloading'),
                    is_bottom = false,
                    request_in_action = false,
                    prev_scroll_pos = 0,
                    offset = config['offset'] === undefined ? 1 : config['offset'],
                    this_is_the_end = false,
                    html_selector = config.html_selector;//'#pl-complete-log > .menu-v';

                function getItems() {
                    if (request_in_action) {
                        return;
                    }
                    $loading.show();
                    request_in_action = true;

                    $.get(config.url, { offset: offset }, function (html) {
                        $loading.hide();
                        html = $(html).find(html_selector).html();
                        if ($.trim(html).length) {
                            offset++;
                        } else {
                            this_is_the_end = true;
                        }
                        $(html_selector).append(html);
                        request_in_action = false;
                    });
                }

                $(window).on('scroll.pl2', function () {
                    if (this_is_the_end) {
                        return;
                    }

                    var scroll_pos = $(document).scrollTop() + $(window).outerHeight(),
                        doc_h = $(document).outerHeight() - 20;

                    if (prev_scroll_pos < scroll_pos) {
                        if (!is_bottom && scroll_pos >= doc_h) {
                            is_bottom = true;

                            getItems();
                        } else {
                            is_bottom = false;
                        }
                    }
                    prev_scroll_pos = scroll_pos;
                });

                if (config['load_now'] !== undefined) {
                    getItems();
                }
            }
        }
    };
}(jQuery));
