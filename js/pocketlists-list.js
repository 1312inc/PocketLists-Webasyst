(function () {
    var list_id = parseInt($('#pl-list-id').val()),
        pocket_id = parseInt($('#pl-pocket-id').val()),
        $loading = $('<i class="icon16 loading">'),
        $new_list_inpit = $('#pl-new-list-input'),
        $new_item_wrapper = $('#pl-item-add').detach(),
        $new_item_input = $new_item_wrapper.find('textarea'),
        $new_item_wrapper_hover = $('<div id="pl-item-add-wrapper-hover" style="display: none;">');

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
        debugger;
        var $this = $(this);
        $this.after($loading);
        $.post(
            '?module=item&action=create',
            {
                list_id: list_id,
                data: data
            },
            function (html) {
                debugger;
                var $li = $this.closest('.pl-item-wrapper');
                var $html = $('' + html + '');
                $html.filter('.pl-item-wrapper').last()
                    .find('.pl-item').first().after($new_item_wrapper);
                $loading.remove();
                $li.after($html);
                $new_item_input.val('').trigger('focus');
            }
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
                debugger;
                $new_item_wrapper.detach().closest('.pl-new-item-wrapper').remove();
            });
        } else {
            $new_item_wrapper.wrap('<li class="pl-new-item-wrapper">').prependTo('.pl-list-items ul.menu-v:first').slideDown(200);
        }
        $new_item_input.focus();
    });
    $new_item_input
        .on('keydown', function (e) {
            var $this = $(this);
            if (e.which === 13) {
                e.preventDefault();
                var parent_id = $this.closest('.menu-v').find('.pl-item-wrapper').first().data('parent-id');
                add_items.call(this, [{
                    name: $this.val().trim(),
                    parent_id: parent_id
                }]);
            }
            if (e.which === 27) {
                $new_item_wrapper.slideUp(200, function () {
                    $new_item_wrapper.detach();
                });
                $new_item_input.val('');
            }
        })
        .on('paste', function (e) {
            var self = this;
            var parent_id = $this.closest('.menu-v').find('.pl-item-wrapper').first().data('parent-id');
            setTimeout(function () {
                var items = $new_item_input.val().trim().split(/\n/);
                var data = [];
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
            }, 100);
        });

    $('.pl-list-items > .menu-v')
        .on('mouseenter', '.pl-item-wrapper > .pl-item', function (e) {
            e.stopPropagation();
            var $item = $(this);
            if (!$item.find($new_item_wrapper).length) { // if no placeholder here
                var $has_children = $item.closest('.pl-item-wrapper').find('.menu-v');
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

    $new_item_wrapper_hover
        .on('click', function (e) {
            $new_item_wrapper_hover.after($new_item_wrapper);
            $new_item_wrapper_hover.detach();
            $new_item_wrapper.slideDown(200);
            $new_item_input.focus();
        });

    $('.pl-done').click(function () {
        $(this).closest('li').slideToggle(200);
    });

    $('#pl-complete-log-link').click(function () {
        $('#pl-complete-log').slideToggle(200);
        $(this).hide(200);
        return false;
    });
}());