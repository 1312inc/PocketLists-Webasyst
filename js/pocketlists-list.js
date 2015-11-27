(function () {
    // todo: refactor...
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
        item_selector = '[data-parent-id]',
        $add_item_link = $('#pl-item-add-link');

    var init_sortable = function () {
        $('#pl-undone-items ul.menu-v').sortable({
            item: item_selector,
            connectWith: "ul.menu-v",
            placeholder: 'pl-item-placeholder',
            tolerance: 'pointer',
            stop: function (event, ui) {
                var $prev = ui.item.parents(item_selector).first(),
                    parent_id = $prev.length ? parseInt($prev.data('id')) : 0;

                ui.item.data('parent-id', parent_id);
                update_sort.call(ui.item, parseInt(ui.item.data('id')));
            }
        });
    };
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
                $('.pl-list-empty').removeClass('pl-list-empty');

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
            data.push({
                id: $this.data('id'),
                parent_id: $this.data('parent-id'),
                sort: i,
                has_children: $this.find(item_selector).length ? 1 : 0
            });
        });
        return data;
    };
    var update_sort = function (id) {
        this.find('label').first().append($loading);
        $.post(
            '?module=item&action=sort',
            {
                list_id: list_id,
                item_id: id ? id : 0,
                data: get_items()
            },
            function (r) {
                if (r.status === 'ok') {
                    init_sortable();
                } else {
                    alert(r.errors);
                }
                $loading.remove();
            },
            'json'
        );
    };
    var complete_item = function (id, status, callback) {
        var $item = this;
        $item.find('.pl-select-label').first().append($loading);
        $item.prop('disabled', true);
        $.post(
            '?module=item&action=complete',
            {
                list_id: list_id,
                id: id,
                status: status
            },
            function (r) {
                if (r.status === 'ok') {
                    // remove from undone list
                    $item.find('ul.menu-v .pl-done').prop('checked', status); // check nesting items
                    $item.find('.pl-done').prop('disabled', false);
                    $item.find('.pl-item-name').toggleClass('gray');
                    setTimeout(function(){
                        $item.slideToggle(200, function () {
                            $item.show();
                            if (status) {
                                $done_items_wrapper.append($item);
                            } else {
                                $undone_items_wrapper.append($item);
                                update_sort.call($item);
                            }

                            // always update list count icon
                            $('#pl-lists')
                                .find('[data-pl-list-id="' + list_id + '"]')
                                .find('.count').text($undone_items_wrapper.find('[data-id]').length);

                            callback && $.isFunction(callback) && callback.call($item);
                        });
                    }, 500);

                } else {
                    alert(r.errors);
                }
                $loading.remove();
            },
            'json'
        );
    };
    var increase_item = function() {
        var $items = $undone_items_wrapper.find('.pl-item-selected').closest(item_selector);
        if ($items.length) {
            $items.each(function () {
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

                    update_sort.call($item, parseInt($item.data('id')));
                }
            });
        }
    };
    var decrease_item = function() {
        var $items = $undone_items_wrapper.find('.pl-item-selected').closest(item_selector);
        if ($items.length) {
            $items.each(function () {
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

                    update_sort.call($item, parseInt($item.data('id')));
                }
            });
        }
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
    $add_item_link.on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
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
    if ($('.pl-list-empty').length) {
        $add_item_link.trigger('click');
    }

    $new_item_input
        .on('keydown', function (e) {
            var $this = $(this);
            if (!e.shiftKey && e.which === 13) {
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

    $list_items_wrapper.on('change', '.pl-is-selected', function (e) {
        var $this = $(this),
            $details = $('#pl-item-details'),
            details_shown = $details.is(':visible'),
            $item_content_wrapper = $this.closest('.pl-item'),
            $item_wrapper = $item_content_wrapper.closest(item_selector),
            is_selected = $item_content_wrapper.hasClass('pl-item-selected');

        e.preventDefault();

        if (!$item_wrapper.find('#pl-item-add').length) {
            if (!details_shown && !is_selected) { // on first click - select
                $details.hide();
                $list_items_wrapper.find('.pl-item').removeClass('pl-item-selected');
                $item_content_wrapper.addClass('pl-item-selected');
                $this.prop('checked', true);
            } else if (!details_shown) { // on second - show details
                $details.html($loading).show();
                $.post(
                    '?module=item&action=details',
                    {
                        id: parseInt($item_wrapper.data('id'))
                    },
                    function (html) {
                        $details.html(html);
                        item_details($details);
                    }
                );
                $this.prop('checked', true);
            } else { // on third
                $details.hide().empty();
                $list_items_wrapper.find('.pl-item').removeClass('pl-item-selected');
                $this.prop('checked', false);
            }
        }
    });

    // action: complete item
    $list_items_wrapper.on('change', '.pl-done', function (e) {
        var $this = $(this),
            $item = $this.closest(item_selector),
            id = parseInt($item.data('id')),
            status = $this.is(':checked') ? 1 : 0;

        complete_item.call($item, id, status);
    });

    $(document).on('keydown', function (e) {
        switch (e.which) {
            case 39: // -->
                increase_item.call(this);
                break;
            case 9: // tab
                if (e.shiftKey) {
                    decrease_item.call(this);
                } else {
                    increase_item.call(this);
                }
                break;
            case 37: // <--
                decrease_item.call(this);
                break;
            case 27: // esc
                if ($('#pl-list-details').is(':visible')) {
                    $('#pl-list-details').hide().empty();
                    $('.pl-list-title').removeData('pl-clicked');
                }
                if ($('#pl-item-details').is(':visible')) {
                    $('#pl-item-details').hide().empty();
                    var $selected = $list_items_wrapper.find('.pl-item-selected');
                    $selected.removeClass('pl-item-selected').prop('checked', false);;
                }
                break;
        }
    });

    init_sortable();

    var item_details = function ($wrapper) {
        var id = 0;
        var init = function () {
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
                constrainInput: false,
                minDate: new Date()
            };

            $wrapper.find('#pl-item-due-datetime').datepicker(datepicker_options);

            handlers();
            id = parseInt($wrapper.find('input[name="item\[id\]"]').val());
        };
        var handlers = function () {
            // save
            $wrapper.on('submit', 'form', function (e) {
                e.preventDefault();
                var $this = $(this);
                $this.find('#pl-item-details-save').after($loading);
                $.post('?module=item&action=data', $this.serialize(), function (html) {
                    $loading.remove();
                    $list_items_wrapper.find('[data-id="' + id + '"] .pl-item').first().replaceWith($(html).addClass('pl-item-selected'));
                });
                return false;
            });
            // cancel
            $wrapper.on('click', '#pl-item-details-cancel', function (e) {
                e.preventDefault();
                $wrapper.hide().empty();
                $('.pl-list-title').removeData('pl-clicked');
            });
            $wrapper.on('click', '#pl-item-priority a', function (e) {
                e.preventDefault();
                $('#pl-item-priority').find('input').val($(this).data('pl-item-priority'));
                $(this).addClass('selected')
                    .siblings().removeClass('selected')
            });
            $wrapper.on('click', '[data-pl-action="item-delete"]', function(e) {
                e.preventDefault();

                $('#pl-dialog-delete-confirm').waDialog({
                    'height': '150px',
                    'min-height': '150px',
                    'width': '400px',
                    onLoad: function() {
                        var $this = $(this);
                    },
                    onSubmit: function (d) {
                        $.post('?module=item&action=delete', { id: id, list_id: list_id }, function(r) {
                            if (r.status === 'ok') {
                                $list_items_wrapper.find('[data-id="' + r.data.id + '"]').remove();
                                $wrapper.find('#pl-item-details-cancel').trigger('click');
                                d.trigger('close');
                            } else {

                            }
                        }, 'json');
                        return false;
                    }
                });
            });
        };

        init();
    };

    $('.pl-list-title').on('click', function(e) {
        var $details = $('#pl-list-details'),
            $this = $(this),
            clicked = $this.data('pl-clicked');

        if (clicked == 1) {
            $details.html($loading).show();
            $this.data('pl-clicked', 2);
            $.post(
                '?module=list&action=details',
                {
                    id: parseInt($('#pl-list-id').val())
                },
                function (html) {
                    $details.html(html);
                    list_details($details);
                }
            );
        } else if (clicked == 2) {
            $this.removeData('pl-clicked');
            $details.hide().empty();
        } else {
            $this.data('pl-clicked', 1);
        }
    });

    var list_details = function ($wrapper) {
        var list_id = 0;
        var icon_path = $wrapper.find('#pl-list-icon-dialog').find('ul').data('pl-icons-path');
        var init = function () {
            handlers();
            list_id = parseInt($wrapper.find('input[name="list\[id\]"]').val());
        };
        var handlers = function () {
            // save
            $wrapper.on('submit', 'form', function (e) {
                e.preventDefault();
                var $this = $(this);
                $this.find('#pl-list-details-save').after($loading);
                $.post('?module=list&action=save', $this.serialize(), function (r) {
                    $loading.remove();
                    if (r.status === 'ok') {
                        update_list_list();
                        $wrapper.find('.success').show().delay(3000).hide();
                    } else {
                        $wrapper.find('.error').show().delay(3000).hide();
                    }
                }, 'json');
                return false;
            });
            // cancel
            $wrapper.on('click', '#pl-list-details-cancel', function (e) {
                e.preventDefault();
                $wrapper.hide().empty();
            });
            $wrapper.on('click', '#pl-list-color a', function (e) {
                e.preventDefault();
                $('#pl-list-color').find('input').val($(this).data('pl-list-color'));
                $(this).addClass('selected')
                    .siblings().removeClass('selected')
            });
            $wrapper.on('click', '#pl-list-icon-change a', function (e) {
                e.preventDefault();
                var $this = $(this);

                $('#pl-list-icon-dialog').waDialog({
                    onLoad: function() {
                        var $dialog = $(this);

                        $('#pl-list-icon-dialog').on('click', 'a[data-pl-list-icon]', function (e) {
                            e.preventDefault();
                            $(this).closest('ul').find('a[data-pl-list-icon]').removeClass('selected');
                            $(this).addClass('selected');
                            $wrapper.find('#pl-list-icon-change').find('input').val($(this).data('pl-list-icon'));
                        })
                    },
                    onSubmit: function (d) {
                        var $selected_icon = $('#pl-list-icon-dialog').find('a[data-pl-list-icon].selected'),
                            icon = $selected_icon.data('pl-list-icon');

                        $this.find('input').val(icon);
                        $wrapper.find('#pl-list-icon-change .listicon48').css('background-image', 'url(' + icon_path + icon + ')');
                        d.trigger('close');
                        return false;
                    }
                });
            });
        };
        var update_list_list = function() {
            // update name
            var name = $wrapper.find('input[name="list\[name\]"]').val(),
                color = $wrapper.find('[data-pl-list-color].selected').data('pl-list-color'),
                icon = $wrapper.find('input[name="list\[icon\]"]').val();
            $('#pl-list-name').text(name);
            // update color
            $('#pl-lists')
                .find('[data-pl-list-id="' + list_id + '"]').removeClass().addClass('pl-' + color)
                .find('.pl-list-name').text(name)
                .end()
                .find('.listicon48').css('background-image', 'url(' + icon_path + icon + ')');
            $('.pl-items').removeClass().addClass('pl-items pl-' + color);
            // update icon
        };

        init();
    };

    $('.pl-items').on('click', '[data-pl-action="list-delete"]', function(e) {
        e.preventDefault();

        $('#pl-dialog-delete-confirm').waDialog({
            'height': '150px',
            'min-height': '150px',
            'width': '400px',
            onLoad: function() {
                var $this = $(this);
            },
            onSubmit: function (d) {
                $.post('?module=list&action=delete', { list_id: list_id }, function(r) {
                    if (r.status === 'ok') {
                        d.trigger('close');
                        $.wa.setHash('#/pocket/1/');
                    } else {

                    }
                }, 'json');
                return false;
            }
        });
    });

    $('.pl-items').on('click', '[data-pl-action="list-archive"]', function(e) {
        e.preventDefault();

        $.post('?module=list&action=archive', { list_id: list_id, archive: 1 }, function(r) {
            if (r.status === 'ok') {
                $.wa.setHash('#/pocket/1/');
            } else {

            }
        }, 'json');
    });

    $('#pl-list-complete').on('click', function (e) {
        e.stopPropagation();

        $('#pl-dialog-list-archive-checkall').waDialog({
            'height': '150px',
            'min-height': '150px',
            'width': '400px',
            onLoad: function() {
                var $this = $(this);
                $this.on('click', '[data-pl-action]', function (e) {
                    e.preventDefault();

                    var $button = $(this),
                        action = $button.data('pl-action');

                    $button.after($loading);

                    if (action === 'list-complete-all') {
                        $.post('?module=item&action=complete', { list_id: list_id, status: 1, id: -1 }, function(r) {
                            if (r.status === 'ok') {
                                $this.trigger('close');
                                //$.wa.setHash('#/pocket/' + pocket_id + '/list/' + list_id );
                                $.pocketlists_routing.redispatch();
                            } else {
                            }
                        }, 'json');
                    } else if (action === 'list-archive') {
                        $.post('?module=list&action=archive', { list_id: list_id, archive: 1 }, function(r) {
                            if (r.status === 'ok') {
                                $this.trigger('close');
                                $.wa.setHash('#/pocket/' + pocket_id);
                            } else {
                            }
                        }, 'json');
                    }
                });
            }
        });
    });

    $('#pl-complete-log-link').click(function () {
        $('#pl-complete-log').slideDown(200);
        $(this).slideUp(200);
        return false;
    });
}());
