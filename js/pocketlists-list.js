(function () {
    "use strict";

    /**
     * object to work with list
     * - add
     * - print
     * - delete
     * - archive
     * - sort all
     */
    $.pocketlists.List = function($list_wrapper) {
        var $new_list_input = $list_wrapper.find('#pl-new-list-input'),
            list_id = parseInt($list_wrapper.find('#pl-list-id').val()),
            pocket_id = parseInt($('#pl-pocket-id').val()),
            $dialog_delete = $('#pl-dialog-delete-confirm'),
            $dialog_complete_all = $('#pl-dialog-list-archive-complete-all');

        /**
         * for show and manipulate with list details
         * - show/hide container (p)
         * - change icon
         * - save details
         */
        var ListDetails = (function ($wrapper) {
            var icon_path = null;

            // show list details container and load html with list details from server
            var showListDetails = function() {
                $.pocketlists.scrollToTop(200, 80);
                $wrapper.html($.pocketlists.$loading).show();

                stickyDetailsSidebar();

                $.post('?module=list&action=details',{ id: list_id }, function (html) {
                    $wrapper.html(html);
                    afterLoad();
                });
            };
            var hideListDetails = function() {
                $wrapper.hide().empty();
                //list.trigger('deselectList.pl2');
            };
            var updateList = function (data) {
                $list_wrapper.find('#pl-list-name').text(data.name); // update name
                if (data.due_date || data.due_datetime) {
                    var due_class = 'pl-due-someday';
                    if (data.calc_priority == 1) {
                        due_class = 'pl-due-tomorrow';
                    } else if(data.calc_priority == 2) {
                        due_class = 'pl-due-today';
                    } else if(data.calc_priority == 3) {
                        due_class = 'pl-due-overdue';
                    }
                    $list_wrapper.find('.pl-list-due').removeClass().addClass('pl-list-due ' + due_class).text(data.due_datetime ? data.due_datetime : data.due_date).show();
                } else {
                    $list_wrapper.find('.pl-list-due').hide();
                }

                // middle sidebar
                $('#pl-lists')
                    .find('[data-pl-list-id="' + list_id + '"]').removeClass().addClass('pl-' + data.color) // update color
                    .find('.pl-list-name').text(data.name) // update name
                    .end()
                    .find('.listicon48').css('background-image', 'url(' + icon_path + data.icon + ')');
                $('.pl-items').removeClass().addClass('pl-items pl-' + data.color); // update icon
            };
            var afterLoad = function() {
                var datepicker_options = {
                    changeMonth: true,
                    changeYear: true,
                    shortYearCutoff: 2,
                    dateShowWeek: false,
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    stepMonths: 1,
                    numberOfMonths: 1,
                    gotoCurrent: true,
                    constrainInput: false
                };

                $wrapper.find('#pl-list-due-datetime').datepicker(datepicker_options);
                icon_path = $wrapper.find('#pl-list-icon-dialog').find('ul').data('pl-icons-path')
            };

            var init = function () {
                if ($wrapper.data('pl-ListDetails')) {
                    return;
                }
                $wrapper.data('pl-ListDetails', true);

                $wrapper
                    .on('submit', 'form', function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        $this.find('#pl-list-details-save').after($.pocketlists.$loading);
                        $.post('?module=list&action=save', $this.serialize(), function (r) {
                            $.pocketlists.$loading.remove();
                            if (r.status === 'ok') {
                                updateList(r.data);
                                hideListDetails();
                            } else {
                                $wrapper.find('.error').show().delay(3000).hide();
                            }
                        }, 'json');
                        return false;
                    }) // save
                    .on('click', '#pl-list-details-cancel', function (e) {
                        e.preventDefault();
                        hideListDetails();
                    })
                    .on('click', '#pl-list-color a', function (e) {
                        e.preventDefault();
                        $('#pl-list-color').find('input').val($(this).data('pl-list-color'));
                        $(this).addClass('selected')
                            .siblings().removeClass('selected')
                    })
                    .on('click', '#pl-list-due-datetime-set', function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        $this.hide().siblings().show().filter('select').prop('disabled', false);
                    })
                    .on('click', '#pl-list-due-datetime-clear', function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        $this.hide().siblings().show().filter('select').hide().prop('disabled', true);
                    })
                    .on('click', '#pl-list-icon-change a', function (e) {
                        e.preventDefault();

                        $('#pl-list-icon-dialog').waDialog({
                            onLoad: function () {
                                var d = $(this);

                                $('#pl-list-icon-dialog').on('click', 'a[data-pl-list-icon]', function (e) {
                                    e.preventDefault();
                                    var icon = $(this).data('pl-list-icon'),
                                        $this = $(this);
                                    $wrapper.find('#pl-list-icon-change').find('input').val($(this).data('pl-list-icon'));

                                    $this.find('input').val(icon);
                                    $wrapper.find('#pl-list-icon-change .listicon48').css('background-image', 'url(' + icon_path + icon + ')');
                                    d.trigger('close');
                                    return false;
                                })
                            }
                        });
                    })
                    .on('show.pl2', showListDetails)
                    .on('hide.pl2', hideListDetails);

                $(window).scroll(function() {
                    stickyDetailsSidebar();
                });
            };
            init();

            return {
                $el: $wrapper,
                trigger: function(event, data) {
                    this.$el && this.$el.trigger(event, data);
                },
                isVisible: function() {
                    return this.$el ? this.$el.is(':visible') : false;
                }
            };
        }($('#pl-list-details')));

        // add list
        var addNewList = function (id) {
            var data = {
                name: $new_list_input.val().trim(),
                type: 'checklist',
                pocket_id: pocket_id
            };
            if (data.name) {
                //$(this).after($.pocketlists.$loading);
                $.post(
                    '?module=list&action=update',
                    {
                        data: data,
                        id: id
                    },
                    function (r) {
                        if (r.status === 'ok') {
                            if (list_id === -1) {
                                $.wa.setHash('#/pocket/' + pocket_id + '/list/' + r.data.id + '/');
                            }
                        } else {

                        }
                    },
                    'json'
                );
            }
        };
        // favorite list
        var favoriteList = function() {
            var $star = $list_wrapper.find('[class*="star"]');
            $.post(
                '?module=list&action=favorite',
                {
                    id: list_id,
                    status: $star.hasClass('star-empty') ? 1 : 0
                },
                function (r) {
                    if (r.status === 'ok') {
                        $star.toggleClass('star-empty star')
                    } else {
                        alert(r.errors);
                    }
                    //$.pocketlists.$loading.remove();
                },
                'json'
            );
        };
        var deleteList = function() {
            $dialog_delete.waDialog({
                'height': '150px',
                'min-height': '150px',
                'width': '400px',
                onLoad: function () {},
                onSubmit: function (d) {
                    $.post('?module=list&action=delete', {list_id: list_id}, function (r) {
                        if (r.status === 'ok') {
                            d.trigger('close');
                            // todo: redirect to allowed pocket/list
                            $.wa.setHash('#/pocket/1/');
                        } else {

                        }
                    }, 'json');
                    return false;
                }
            });
        };
        var archiveItem = function() {
            $.post('?module=list&action=archive', {list_id: list_id, archive: 1}, function (r) {
                if (r.status === 'ok') {
                    // todo: change pocket
                    $.wa.setHash('#/pocket/1/');
                } else {

                }
            }, 'json');
        };
        var autoSort = function() {
            $.post('?module=list&action=sort', {list_id: list_id}, function (r) {
                if (r.status === 'ok') {
                    $.pocketlists_routing.redispatch();
                } else {

                }
            }, 'json');
        };
        var deselectList = function() {
            $list_wrapper.removeData('pl-clicked');
        };

        var init = function() {
            // will add new list if we can
            if ($new_list_input.length) {
                $new_list_input.focus();
                $new_list_input.on('keydown', function (e) {
                    if (e.which === 13) {
                        e.preventDefault();
                        addNewList(list_id);
                    }
                });
            }

            $list_wrapper
                .on('click', function (e) {
                    var clicked = $list_wrapper.data('pl-clicked');

                    // ignore when click on checkbox
                    if ($(e.target).closest('.pl-done-label').length) {
                        return;
                    }
                    if (clicked == 1) {
                        $list_wrapper.data('pl-clicked', 2);

                        $.pocketlists.scrollToTop(200, 80);

                        //$.pocketlists.ListDetails.show();
                        ListDetails.trigger('show.pl2');
                    } else if (clicked == 2) {
                        //$.pocketlists.ListDetails.hide();
                        ListDetails.trigger('hide.pl2');
                        deselectList();
                    } else {
                        $list_wrapper.data('pl-clicked', 1);
                    }
                }) // open details
                .on('click', '[data-pl-action="list-delete"]', function (e) {
                    e.preventDefault();

                    deleteList();
                })
                .on('click', '[data-pl-action="list-archive"]', function (e) {
                    e.preventDefault();

                    archiveItem();
                })
                .on('click', '[data-pl-action="list-sort"]', function (e) {
                    e.preventDefault();

                    autoSort();
                })
                .on('click', '[data-pl-action="list-favorite"]', function (e) {
                    e.preventDefault();

                    favoriteList();
                })
                .on('click', '#pl-list-complete', function (e) {
                    e.stopPropagation();

                    $dialog_complete_all.waDialog({
                        'height': '150px',
                        'min-height': '150px',
                        'width': '400px',
                        onLoad: function () {
                            var $this = $(this);
                            $this.on('click', '[data-pl-action]', function (e) {
                                e.preventDefault();

                                var $button = $(this),
                                    action = $button.data('pl-action');

                                $button.after($.pocketlists.$loading);

                                if (action === 'list-complete-all') {
                                    $.post('?module=list&action=complete', {list_id: list_id, status: 1}, function (r) {
                                        if (r.status === 'ok') {
                                            $this.trigger('close');
                                            //$.wa.setHash('#/pocket/' + pocket_id + '/list/' + list_id );
                                            $.pocketlists_routing.redispatch();
                                        } else {
                                        }
                                    }, 'json');
                                } else if (action === 'list-archive') {
                                    $.post('?module=list&action=archive', {list_id: list_id, archive: 1}, function (r) {
                                        if (r.status === 'ok') {
                                            $this.trigger('close');
                                            $.wa.setHash('#/pocket/' + pocket_id);
                                        } else {
                                        }
                                    }, 'json');
                                } else if (action === 'cancel') {
                                    $('#pl-list-complete').prop('checked', false);
                                    $this.trigger('close');
                                    $.pocketlists.$loading.remove();
                                }
                            });
                        }
                    });
                })
                .on('deleteList.pl2', deleteList)
                .on('deselectList.pl2', deselectList);

            // keyboard
            $(document).on('keydown', function (e) {
                switch (e.which) {
                    case 27: // esc
                        ListDetails.isVisible() && ListDetails.trigger('hide.pl2');
                        break;
                }
            });

            //this.list_details = list_details;
        };

        init();

        return {
            $el: $list_wrapper,
            list_details: ListDetails,
            list_id: list_id,
            pocket_id: pocket_id
        }
    };

    /**
     * for items
     * - add
     * - complete
     * - replace
     * - drag
     */
    $.pocketlists.Items = function($list_items_wrapper, options) {
        var $undone_items_wrapper = $list_items_wrapper.find('#pl-undone-items > ul.menu-v'),
            $sortable_items = $('#pl-undone-items ul.menu-v'),
            $done_items = $list_items_wrapper.find('#pl-complete-log'),
            $done_items_wrapper = $done_items.find('ul.menu-v').first(),
            $new_item_wrapper = $('#pl-item-add').detach(),
            $new_item_input = $new_item_wrapper.find('textarea'),
            $new_item_wrapper_hover = $('<div id="pl-item-add-wrapper-hover" style="display: none;">'),
            item_selector = '[data-parent-id]',
            $add_item_link = $('#pl-item-add-link'),
            $show_logbook_items = $('#pl-complete-log-link'),
            $empty_list_msg = $list_items_wrapper.find('#pl-empty-list-msg'),
            $current_item = null,
            defaults = {
                enableAddLinkOnHover: true,
                enableChangeLevel: true,
                enableSortItems: true,
                list: null,
                assignUser: null,
                showMessageOnEmptyList: false
            },
            o = {};

        // sortable items
        var initSortable = function () {
            if (o.enableSortItems) {
                $sortable_items.sortable({
                    item: item_selector,
                    connectWith: "ul.menu-v",
                    placeholder: 'pl-item-placeholder',
                    tolerance: 'pointer',
                    stop: function (event, ui) {
                        var $prev = ui.item.parents(item_selector).first(),
                            parent_id = $prev.length ? parseInt($prev.data('id')) : 0;

                        ui.item.data('parent-id', parent_id);
                        updateSort(parseInt(ui.item.data('id')));
                    }
                });
            }
        };
        // save item
        var addItem = function (data, callback) {
            var $this = $(this);
//             debugger;
//             if (o.assignUser) {
//                 $.each(data, function() {
//                      data['assigned_contact_id'] = o.assignUser;
//                 });
//             }
            //$this.after($.pocketlists.$loading);
            $.post(
                '?module=item&action=create',
                {
                    list_id: o.list ? o.list.list_id : 0,
                    data: data,
                    assigned_contact_id: o.assignUser ? o.assignUser : false
                },
                function (html) {
                    var $li = $this.closest(item_selector);
                    var $html = $('' + html + '');

                    $new_item_input.data('can_blur', false);

                    if ($li.length) {
                        if (!$li.parents(item_selector).length || // not root item
                                //$li.prev(item_selector).length || // not first item in subs
                            $new_item_wrapper.prev('.pl-item').length) { // new item wrapper is after item
                            $li.after($html);
                        } else {
                            $li.before($html);
                        }
                    } else {
                        $undone_items_wrapper.prepend($html);
                    }
                    $html.filter(item_selector).last()
                        .find('.pl-item').first().after($new_item_wrapper);

                    //$.pocketlists.$loading.remove();

                    $new_item_input.val('').trigger('focus').css('height', 'auto').data('can_blur', true);

                    $.isFunction(callback) && callback.call($this);

                    hideEmptyListMessage();
                    updateListCountBadge();
                    updateSort();
                }
            );
        };
        // get all items list
        var getItems = function () {
            var data = [];
            $undone_items_wrapper.find(item_selector).each(function (i) {
                var $this = $(this);
                data.push({
                    id: $this.data('id'),
                    parent_id: $this.data('parent-id'),
                    sort: i,
                    has_children: $this.find(item_selector).length ? 1 : 0
                });
            });
            return data;
        };
        // update sort base on current positions
        var updateSort = function (id) {
            //this.find('label').first().append($.pocketlists.$loading);
            if (o.enableSortItems && o.list) {
                $.post(
                    '?module=item&action=sort',
                    {
                        list_id: o.list.list_id,
                        item_id: id ? id : 0,
                        data: getItems()
                    },
                    function (r) {
                        if (r.status === 'ok') {
                            initSortable();
                        } else {
                            alert(r.errors);
                        }
                        //$.pocketlists.$loading.remove();
                    },
                    'json'
                );
            }
        };
        // complete/uncomplete items
        var completeItem = function ($item, status, callback) {
            //$item.find('.pl-select-label').first().append($.pocketlists.$loading);
            var id = parseInt($item.data('id'));

            $item.prop('disabled', true);
            $.post(
                '?module=item&action=complete',
                {
                    id: id,
                    status: status
                },
                function (r) {
                    if (r.status === 'ok') {
                        $.pocketlists.updateAppCounter();
                        // remove from undone list
                        $item.find('ul.menu-v').find(':checkbox').prop('checked', status); // check nesting items
                        $item.find('.pl-done').prop('disabled', false);
                        $item.find('.pl-item-name').toggleClass('gray');
                        setTimeout(function () {
                            $item.slideToggle(200, function () {
                                $item.show();
                                if (status) {
                                    $done_items_wrapper.append($item);
                                } else {
                                    $undone_items_wrapper.append($item);
                                    updateSort();
                                }

                                // always update list count icon
                                updateListCountBadge();

                                $('#pl-complete-log-link').find('i').text($_('Show all ' + $done_items_wrapper.find('[data-id]').length + ' completed to-dos')); // update "complete items" heading

                                showEmptyListMessage();

                                callback && $.isFunction(callback) && callback.call($item);
                            });
                        }, 800);

                    } else {
                        alert(r.errors);
                    }
                    $.pocketlists.$loading.remove();
                },
                'json'
            );
        };
        // increase item level
        var increaseItem = function (e) {
            //var $items = $undone_items_wrapper.find('.pl-item-selected').closest(item_selector);
            if (o.enableChangeLevel && $current_item) {
                e.preventDefault();
                e.stopPropagation();
                $current_item.each(function () {
                    var $item = $(this),
                        $prev = $item.prev(item_selector);
                    if ($prev.length) { // not first
                        var parent_id = parseInt($prev.data('id'));
                        $item.data('parent-id', parent_id); // update parent id

                        var $nested = $prev.find('ul.menu-v').first();
                        if ($nested.length) {
                            $nested.append($item);
                        } else {
                            $prev.append($('<ul class="menu-v">').html($item));
                        }

                        updateSort(parseInt($item.data('id')));
                    }
                });
            }
        };
        // decrease item level
        var decreaseItem = function (e) {
            //var $items = $undone_items_wrapper.find('.pl-item-selected').closest(item_selector);
            if (o.enableChangeLevel && $current_item) {
                e.preventDefault();
                e.stopPropagation();
                $current_item.each(function () {
                    var $item = $(this),
                        $prev = $item.parents(item_selector).first();
                    if ($prev.length) { // not first level
                        var parent_id = parseInt($prev.data('parent-id'));

                        $item.data('parent-id', parent_id); // update parent id

                        var $items_same_level = $item.nextAll(), // all next items on same level
                            $item_children_wrapper = $item.find('ul.menu-v'); // item children wrapper

                        if (!$item_children_wrapper.length) { // create if not exist
                            $item_children_wrapper = $('<ul class="menu-v">');
                            $item.append($item_children_wrapper);
                        }
                        $item_children_wrapper.append($items_same_level); // now will be children of current

                        $prev.after($item);

                        updateSort(parseInt($item.data('id')));
                    }
                });
            }
        };
        // favorite item
        var favoriteItem = function($item) {
            var $star = $item.find('[class*="star"]'),
                id = parseInt($item.data('id'));
            $.post(
                '?module=' + type + '&action=favorite',
                {
                    id: id,
                    status: $star.hasClass('star-empty') ? 1 : 0
                },
                function (r) {
                    if (r.status === 'ok') {
                        $star.toggleClass('star-empty star')
                    } else {
                        alert(r.errors);
                    }
                    //$.pocketlists.$loading.remove();
                },
                'json'
            );
        };
        // select clicked item
        var selectItem = function($item) {
            $current_item = $item;
            if ($current_item) {
                var $item_data_wrapper = $current_item.find('.pl-item').first();

                $list_items_wrapper.find('.pl-item').removeClass('pl-item-selected'); // remove selected class from all items
                $item_data_wrapper.addClass('pl-item-selected'); // add to currently clicked item
                $current_item.prop('checked', true);
            }
        };
        // deselect clicked item
        var deselectItem = function() {
            if ($current_item) {
                $list_items_wrapper.find('.pl-item').removeClass('pl-item-selected');
                $current_item.prop('checked', false);
                $current_item = null;
            }
        };
        // remove item row
        var removeItem = function(id) {
            $list_items_wrapper.find('[data-id="' + id + '"]').remove();
        };
        var replaceItem = function(data) {
            if ($current_item) {
                $current_item.find('.pl-item').first().replaceWith($(data).addClass('pl-item-selected'));
            }
        };
        var updateListCountBadge = function() {
            if (o.list && o.list.list_id) {
                $('#pl-lists')
                    .find('[data-pl-list-id="' + o.list.list_id + '"]')
                    .find('.count').text($undone_items_wrapper.find('[data-id]').length);
            }
        };
        var isEmptyList = function() {
            return !getItems().length;
        };
        var showEmptyListMessage = function() {
            isEmptyList() && o.showMessageOnEmptyList && $empty_list_msg.show();
        };
        var hideEmptyListMessage = function() {
            o.showMessageOnEmptyList && $empty_list_msg.hide();
        };
        /**
         * for new item dom manipulating
         */
        var NewItemWrapper = function() {
            var resizeTextarea = function () {
                $new_item_input.css('height', 'auto');
                $new_item_input.css('height', ($new_item_input.get(0).scrollHeight - parseInt($new_item_input.css('padding-top')) - parseInt($new_item_input.css('padding-bottom'))) + 'px');
            };

            var hide_new_item_wrapper = function () {
                $new_item_wrapper.slideUp(200, function () {
                    $new_item_wrapper.detach();
                    $('.pl-new-item-wrapper').remove();
                    $new_item_input.val('');
                    showEmptyListMessage();
                });
            };

            var init = function () {
                // adding new item by clickin on top link
                $add_item_link.on('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    function show_new_item_wrapper() {
                        hideEmptyListMessage();
                        $new_item_wrapper.prependTo($undone_items_wrapper).slideDown(200, function () {
                            $new_item_input.focus();
                        }).wrap('<li class="pl-new-item-wrapper">');
                    }

                    if ($new_item_wrapper.is(':visible')) {
                        $new_item_wrapper.slideUp(200, function () {
                            $new_item_wrapper.detach();
                            $('.pl-new-item-wrapper').remove();
                            show_new_item_wrapper();
                        });
                    } else {
                        show_new_item_wrapper();
                    }

                });
                if (isEmptyList() && !o.showMessageOnEmptyList) { // show new item input on empty list
                    $add_item_link.trigger('click');
                }
                $new_item_input
                    .on('change cut keydown drop paste', function () {
                        window.setTimeout(resizeTextarea, 0);
                    })
                    .on('keydown', function (e) {
                        var $this = $(this);
                        $this.data('can_blur', true);
                        if (!e.shiftKey && e.which === 13) {
                            e.preventDefault();
                            var parent_id = $this.closest('.menu-v').find(item_selector).first().data('parent-id'),
                                name = $this.val().trim();
                            if (name) {
                                addItem.call(this, [{
                                    name: name,
                                    parent_id: parent_id
                                }]);
                            }
                        } else if (e.which === 27) {
                            hide_new_item_wrapper();
                        }
                    })
                    .on('paste', function () {
                        var parent_id = $(this).closest('.menu-v').find(item_selector).first().data('parent-id');
                        var self = this;
                        setTimeout(function () {
                            var items = $new_item_input.val().split(/\n/);
                            var data = [];
                            if (items.length > 1) {
                                for (var i = 0; i < items.length; i++) {
                                    var name = $.trim(items[i]);
                                    if (name) {
                                        data.push({
                                            name: name,
                                            parent_id: parent_id
                                        });
                                    }
                                }
                                addItem.call(self, data);
                            }
                        }, 100);
                    })
                    .on('blur', function () {
                        var $this = $(this),
                            parent_id = $this.closest('.menu-v').find(item_selector).first().data('parent-id'),
                            name = $this.val().trim(),
                            can_blur = $this.data('can_blur');

                        if (can_blur) {
                            if (name) {
                                addItem.call(this, [{
                                    name: name,
                                    parent_id: parent_id
                                }], hide_new_item_wrapper);
                            } else {
                                hide_new_item_wrapper();
                            }
                        }
                    });

                var undone_items_wrapper_hover_timeout = null;
                if (o.enableAddLinkOnHover) {
                    $undone_items_wrapper
                        .on('mouseenter', item_selector + ' > .pl-item', function (e) {
                            e.stopPropagation();
                            var $item = $(this);
                            undone_items_wrapper_hover_timeout = setTimeout(function () {
                                if (!$item.find($new_item_wrapper).length) { // if no placeholder here
                                    $item.find('.pl-select-label').append($new_item_wrapper_hover.show());
                                }
                            }, 500);
                        })
                        .on('mouseleave', item_selector + ' > .pl-item', function () {
                            clearTimeout(undone_items_wrapper_hover_timeout);
                            $new_item_wrapper_hover.detach();
                        });

                    $new_item_wrapper_hover.on('click', function () {
                        // if item has children - place it before first
                        var $item = $(this);
                        var $has_children = $item.closest(item_selector).find('.menu-v');

                        $new_item_input.data('can_blur', false);
                        if ($has_children.length) { // if item has children - indent
                            $has_children.find('.pl-item').first().before($new_item_wrapper);
                        } else { // else on same level
                            $item.closest(item_selector).find('.pl-item').first().after($new_item_wrapper);
                        }
                        $new_item_wrapper_hover.detach();
                        $new_item_wrapper.slideDown(200);
                        $new_item_input.focus();
                        $new_item_input.data('can_blur', true);
                    });
                }
            };

            init();
        };

        /**
         * for item details
         * - show/hide (p)
         * - change details
         */
        var ItemDetails = (function ($wrapper) {
            var id = 0,
                $dialog_confirm = $('#pl-dialog-delete-confirm');

            var hideItemDetails = function () {
                $wrapper.hide().empty();
                id = 0;
                $list_items_wrapper.trigger('deselectItem.pl2');
            };
            var showItemDetails = function (id_item) {
                id = id_item;
                $wrapper.html($.pocketlists.$loading).show();
                stickyDetailsSidebar();
                $.post('?module=item&action=details',{ id: id }, function (html) {
                    $wrapper.html(html);
                    afterLoad();
                });
            };
            var afterLoad = function() {
                var datepicker_options = {
                    changeMonth: true,
                    changeYear: true,
                    shortYearCutoff: 2,
                    dateShowWeek: false,
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    stepMonths: 1,
                    numberOfMonths: 1,
                    gotoCurrent: true,
                    constrainInput: false
                };

                $wrapper.find('#pl-item-due-datetime').datepicker(datepicker_options);
            };

            var init = function () {
                if ($wrapper.data('pl-ListDetails')) {
                    return;
                }
                $wrapper.data('pl-ListDetails', true);

                //id = parseInt($wrapper.find('input[name="item\[id\]"]').val());
                $wrapper
                    .on('submit', 'form', function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        $this.find('#pl-item-details-save').after($.pocketlists.$loading);
                        $.post('?module=item&action=data', $this.serialize(), function (html) {
                            $.pocketlists.updateAppCounter();
                            $.pocketlists.$loading.remove();
                            $list_items_wrapper.trigger('replaceSelectedItem.pl2', [html]);
                            hideItemDetails();
                        });
                        return false;
                    })
                    .on('click', '#pl-item-details-cancel', function (e) {
                        e.preventDefault();

                        hideItemDetails();
                    })
                    .on('click', '#pl-item-priority a', function (e) {
                        e.preventDefault();
                        $('#pl-item-priority').find('input').val($(this).data('pl-item-priority'));
                        $(this).addClass('selected').siblings().removeClass('selected')
                    })
                    .on('click', '#pl-item-due-datetime-set', function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        $this.hide().siblings().show().filter('select').prop('disabled', false);
                    })
                    .on('click', '#pl-item-due-datetime-clear', function (e) {
                        e.preventDefault();
                        var $this = $(this);
                        $this.hide().siblings().show().filter('select').hide().prop('disabled', true);
                    })
                    .on('click', '[data-pl-action="item-delete"]', function (e) {
                        e.preventDefault();

                        $dialog_confirm.waDialog({
                            'height': '150px',
                            'min-height': '150px',
                            'width': '400px',
                            onLoad: function () {},
                            onSubmit: function (d) {
                                $.post('?module=item&action=delete', {id: id, list_id: list.list_id}, function (r) {
                                    if (r.status === 'ok') {
                                        $list_items_wrapper.trigger('deselectItem.pl2', [r.data.id]);
                                        hideItemDetails();
                                        d.trigger('close');
                                    } else {

                                    }
                                }, 'json');
                                return false;
                            }
                        });
                    })
                    .on('change', '#pl-assigned-contact select', function () {
                        var assigned_contact_id = $(this).val();
                        $('#pl-assigned-contact').find('[data-pl-contact-id="' + assigned_contact_id + '"]').show().siblings().hide();
                    })
                    .on('show.pl2', function(e, id){
                        showItemDetails(id);
                    })
                    .on('hide.pl2', hideItemDetails);

                $(window).scroll(function() {
                    stickyDetailsSidebar();
                });
            };

            init();

            return {
                $el: $wrapper,
                trigger: function(event, data) {
                    this.$el && this.$el.trigger(event, data);
                },
                isVisible: function() {
                    return this.$el ? this.$el.is(':visible') : false;
                }
            };
        }($('#pl-item-details')));

        var init = function() {
            o = $.extend({}, defaults, options);

            if ($.pocketlists_routing.getHash() == '#/todo/' &&
                $.pocketlists_routing.getHash().indexOf('/team/') > 0) {
                $new_item_wrapper.prependTo($undone_items_wrapper).slideDown(200).wrap('<li class="pl-new-item-wrapper">');
                $new_item_input.focus();
            }

            showEmptyListMessage();

            initSortable();
            $add_item_link.length && new NewItemWrapper();

            $list_items_wrapper
                .on('change', '.pl-done', function () {
                    var $this = $(this),
                        $item = $this.closest(item_selector),
                        status = $this.is(':checked') ? 1 : 0;

                    completeItem($item, status);
                }) // action: complete item
                .on('change', '.pl-is-selected', function (e) {
                    var $this = $(this),
                        $item = $this.closest(item_selector),
                        is_selected = ($current_item && $current_item.data('id') == $item.data('id')) ? true : false;
                    e.preventDefault();

                    if (!$item.find('#pl-item-add').length) {
                        if (!ItemDetails.isVisible() && !is_selected) { // on first click - select
                            ItemDetails.trigger('hide.pl2');
                            selectItem($item);
                        } else if (!ItemDetails.isVisible()) { // on second - show details
                            ItemDetails.trigger('show.pl2', [parseInt($item.data('id'))]); // show item details
                            selectItem($item);
                        } else { // on third
                            ItemDetails.trigger('hide.pl2');
                            deselectItem();
                        }
                    }
                }) // action: select item
                .on('click', '.pl-favorite', function(e) {
                    e.preventDefault();
                    var $this = $(this),
                        $item = $this.closest(item_selector);

                    favoriteItem($item);
                }) // action: favorite item
                .on('increaseSelectedItem.pl2', function(e) {
                    increaseItem(e);
                })
                .on('decreaseSelectedItem.pl2', function(e) {
                    decreaseItem(e);
                })
                .on('deselectItem.pl2', deselectItem)
                .on('removeItem.pl2', function(e, id) {
                    removeItem(id);
                })
                .on('replaceSelectedItem.pl2', function(e, data) {
                    replaceItem(data);
                });

            // keyboard
            $(document).on('keydown', function (e) {
                switch (e.which) {
                    //case 39: // -->
                    //    increase_item.call(this);
                    //    break;
                    case 9: // tab
                        if (!(o.list && o.list.list_details.isVisible()) && !ItemDetails.isVisible()) {
                            if (e.shiftKey) {
                                decreaseItem(e);
                            } else {
                                increaseItem(e);
                            }
                        }
                        break;
                    //case 37: // <--
                    //    decrease_item.call(this);
                    //    break;
                    case 27: // esc
                        ItemDetails.isVisible() && ItemDetails.trigger('hide.pl2');
                        break;
                }
            });

            // show logbook items
            $show_logbook_items.click(function () {
                $done_items.slideDown(200);
                $(this).slideUp(200);
                return false;
            });
        };

        init();

        return {
            $el: $list_items_wrapper,
            trigger: function(event, data) {
                this.$el && this.$el.trigger(event, data);
            }
        };
    };

    function stickyDetailsSidebar() {
        var list_top_offset = $('#pl-list-content').offset().top;
        var _viewport_top_offset = $(window).scrollTop();
        var _window_height = $(window).height();

        if ($('.pl-details .fields form').height() > _window_height)
            return;

        if ( _viewport_top_offset > list_top_offset)
        {
            $('.pl-details').addClass('sticky');
            var _viewport_bottom_offset = $(document).height() - _window_height - _viewport_top_offset;

            $('.pl-details').css('bottom', Math.max(0, 16-_viewport_bottom_offset)+'px');

        }
        else
        {
            $('.pl-details').removeClass('sticky');
        }
    }
}());
