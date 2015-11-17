(function () {
    var list_id = parseInt($('#pl-list-id').val()),
        pocket_id = parseInt($('#pl-pocket-id').val()),
        $list_items_wrapper = $('#pl-list-items'),
        $undone_items_wrapper = $('#pl-undone-items > ul.menu-v'),
        $done_items_wrapper = $('#pl-complete-log > ul.menu-v'),
        $loading = $('<i class="icon16 loading">'),
        $new_list_inpit = $('#pl-new-list-input'),
        $new_item_wrapper = $('#pl-item-add').detach(),
        $new_item_input = $new_item_wrapper.find('textarea'),
        $new_item_wrapper_hover = $('<div id="pl-item-add-wrapper-hover" style="display: none;">'),
        item_selector = '[data-parent-id]';

    var update_list = function (id) {
        var data = {
            name: $(this).val().trim(),
            type: 'checklist',
            pocket_id: pocket_id
        };
        if (data.name) {
            $(this).after($loading);
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
    var add_items = function (data) {
        var $this = $(this);
        $this.after($loading);
        $.post(
            '?module=item&action=create',
            {
                list_id: list_id,
                data: data
            },
            function (html) {
                var $li = $this.closest(item_selector);
                var $html = $('' + html + '');
                $html.filter(item_selector).last()
                    .find('.pl-item').first().after($new_item_wrapper);
                if ($li.length) {
                    $li.after($html);
                } else {
                    $undone_items_wrapper.prepend($html);
                }
                $loading.remove();
                $new_item_input.val('').trigger('focus');

                update_sort.call($html);
            }
        );
    };
    var move_item = function (data, callback) {
        $.post(
            '?module=item&action=move',
            {
                list_id: list_id,
                data: data
            },
            function (r) {
                if (r.status === 'ok') {
                    $.isFunction(callback) && callback.call();
                } else {
                    alert(r.errors);
                }
            },
            'json'
        );
    };
    var get_items = function () {
        var data = [];
        $undone_items_wrapper.find(item_selector).each(function (i) {
            var $this = $(this);
            data.push({id: $this.data('id'), parent_id: $this.data('parent-id'), sort: i});
        });
        return data;
    };
    var update_sort = function (callback) {
        this.find('label').first().append($loading);
        $.post(
            '?module=item&action=sort',
            {
                list_id: list_id,
                data: get_items()
            },
            function (r) {
                if (r.status === 'ok') {
                    init_sortable();
                    callback && $.isFunction(callback) && callback.call();
                } else {
                    alert(r.errors);
                }
                $loading.remove();
            },
            'json'
        );
    };
    var complete_item = function (id, status, callback) {
        this.find('label').first().append($loading);
        $.post(
            '?module=item&action=complete',
            {
                list_id: list_id,
                id: id,
                status: status
            },
            function (r) {
                if (r.status === 'ok') {
                    // remove from
                    callback && $.isFunction(callback) && callback.call();
                } else {
                    alert(r.errors);
                }
                $loading.remove();
            },
            'json'
        );
    };
    if ($new_list_inpit.length) {
        $new_list_inpit.focus();
        $new_list_inpit.on('keydown', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                update_list.call(this, list_id);
            }
        });
    }
    $('#pl-item-add-link').click(function (e) {
        e.preventDefault();
        if ($new_item_wrapper.is(':visible')) {
            $new_item_wrapper.slideUp(200, function () {
                $new_item_wrapper.detach();
                $('.pl-new-item-wrapper').remove();
            });
        } else {
            $new_item_wrapper.prependTo($undone_items_wrapper).slideDown(200).wrap('<li class="pl-new-item-wrapper">');
        }
        $new_item_input.focus();
    });
    $new_item_input
        .on('keydown', function (e) {
            var $this = $(this);
            if (e.which === 13) {
                e.preventDefault();
                var parent_id = $this.closest('.menu-v').find(item_selector).first().data('parent-id');
                add_items.call(this, [{
                    name: $this.val().trim(),
                    parent_id: parent_id
                }]);
            } else if (e.which === 27) {
                $new_item_wrapper.slideUp(200, function () {
                    $new_item_wrapper.detach();
                    $('.pl-new-item-wrapper').remove();
                });
                $new_item_input.val('');
            }
        })
        .on('paste', function (e) {
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
                    add_items.call(self, data);
                }
            }, 100);
        });

    $undone_items_wrapper
        .on('mouseenter', item_selector + ' > .pl-item', function (e) {
            e.stopPropagation();
            var $item = $(this);
            if (!$item.find($new_item_wrapper).length) { // if no placeholder here
                var $has_children = $item.closest(item_selector).find('.menu-v');
                if ($has_children.length) { // if item has children - indent
                    $has_children.find('.pl-item').first().before($new_item_wrapper_hover.show())
                } else { // else on same level
                    $item.after($new_item_wrapper_hover.show());
                }
            }
        })
        .on('mouseleave', function (e) {
            $new_item_wrapper_hover.detach();
        });

    $new_item_wrapper_hover.on('click', function (e) {
        $new_item_wrapper_hover.after($new_item_wrapper);
        $new_item_wrapper_hover.detach();
        $new_item_wrapper.slideDown(200);
        $new_item_input.focus();
    });

    $list_items_wrapper.on('change', '.pl-is-selected', function () {
        $('#pl-item-details').toggle();
        $undone_items_wrapper.find('.pl-item').removeClass('pl-item-selected');
        $(this).closest('.pl-item').toggleClass('pl-item-selected')
    });

    // action: complete item
    $list_items_wrapper.on('change', '.pl-done', function () {
        var $this = $(this),
            $item = $this.closest(item_selector),
            id = parseInt($item.data('id')),
            status = $this.is(':checked') ? 1 : 0;

        complete_item.call($item, id, status, function () {
            $item.slideToggle(200, function () {
                debugger;
                $item.show();
                if (status) {
                    $done_items_wrapper.append($item);
                } else {
                    $undone_items_wrapper.append($item);
                    update_sort.call($item);
                }
            });

        });

    });

    $(document).on('keydown', function (e) {
        var $items = $undone_items_wrapper.find('.pl-item-selected').closest(item_selector);
        if (e.which === 39) { // -->
            if ($items.length) {
                $items.each(function () {
                    var $item = $(this),
                        $prev = $item.prev(item_selector),
                        $next = $prev.next(item_selector);
                    if ($prev.length) { // not first
                        var parent_id = parseInt($prev.data('id')),
                            item_id = parseInt($item.data('id')),
                            before_id = $next.length ? parseInt($next.data('id')) : 0,
                            data = [{id: item_id, parent_id: parent_id, before_id: before_id}];
                        $item.data('parent-id', parent_id); // update parent id

                        //move_item([{id: item_id, parent_id: parent_id}], function () {
                        //    var $nested = $prev.find('ul').first();
                        //    if ($nested.length) {
                        //        $nested.append($item);
                        //    } else {
                        //        $prev.append($('<ul class="menu-v">').html($item));
                        //    }
                        //});
                        update_sort.call($item, function () {
                            var $nested = $prev.find('ul').first();
                            if ($nested.length) {
                                $nested.append($item);
                            } else {
                                $prev.append($('<ul class="menu-v">').html($item));
                            }
                        });
                    }
                });
            }
        } else if (e.which === 37) { // <--
            if ($items.length) {
                $items.each(function () {
                    var $item = $(this),
                        $prev = $item.parents(item_selector).first(),
                        $next = $prev.next(item_selector);
                    if ($prev.length) { // not first level
                        var parent_id = parseInt($prev.data('parent-id')),
                            item_id = parseInt($item.data('id')),
                            before_id = $next.length ? parseInt($next.data('id')) : 0,
                            data = [{id: item_id, parent_id: parent_id, before_id: before_id}];
                        $item.data('parent-id', parent_id); // update parent id

                        var $items_same_level = $item.nextAll(), // all next items on same level
                            $item_children_wrapper = $item.find('ul.menu-v'); // item children wrapper

                        $items_same_level.each(function () {
                            $items_same_level.data('parent-id', item_id);
                            data.push({
                                id: $(this).data('id'),
                                parent_id: item_id
                            });
                        });

                        //move_item(data, function () {
                        //    if (!$item_children_wrapper.length) { // create if not exist
                        //        $item_children_wrapper = $('<ul class="menu-v">');
                        //        $item.append($item_children_wrapper);
                        //    }
                        //    $item_children_wrapper.append($items_same_level); // now will be children of current
                        //
                        //    $prev.after($item);
                        //});
                        update_sort.call($item, function () {
                            if (!$item_children_wrapper.length) { // create if not exist
                                $item_children_wrapper = $('<ul class="menu-v">');
                                $item.append($item_children_wrapper);
                            }
                            $item_children_wrapper.append($items_same_level); // now will be children of current

                            $prev.after($item);
                        });
                    }
                });
            }
        }
    });


    var init_sortable = function () {
        $undone_items_wrapper.sortable({
            connectWith: "ul.menu-v",
            placeholder: 'pl-item-placeholder',
            stop: function (event, ui) {
                var $prev = ui.item.parents(item_selector).first(),
                    parent_id = $prev.length ? parseInt($prev.data('id')) : 0;

                ui.item.data('parent-id', parent_id);
                update_sort.call(ui.item);
            }
        });
    };

    init_sortable();

    $('#pl-complete-log-link').click(function () {
        $('#pl-complete-log').slideToggle(200);
        $(this).hide(200);
        return false;
    });
}());