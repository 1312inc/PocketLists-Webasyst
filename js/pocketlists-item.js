"use strict";

/**
 * for items
 * - add
 * - complete
 * - replace
 * - drag
 */
$.pocketlists.Items = function ($list_items_wrapper, options) {
    var $undone_items_wrapper = $list_items_wrapper.find('[data-pl-items="undone"] > ul.menu-v'),
        $sortable_items = $('[data-pl-items="undone"]').find('ul.menu-v'),
        $done_items = $list_items_wrapper.find('#pl-complete-log'),
        $done_items_wrapper = $done_items.find('ul.menu-v').first(),
        item_selector = '[data-parent-id]',
        // $add_item_link = $('#pl-item-add-link'),
        $show_logbook_items = $('#pl-complete-log-link'),
        $empty_list_msg = $list_items_wrapper.find('#pl-welcome-msg'),
        $current_item = null,
        o = $.extend({}, {
            enableAddLinkOnHover: true,
            enableChangeLevel: true,
            enableSortItems: true,
            list: null,
            assignUser: null,
            showMessageOnEmptyList: false,
            dueDate: '',
            filter: false,
            archive: false,
            allowChat: true,
            current_user_id: 0,
            defaultLinkedEntity: false,
            standAloneItemAdd: false,
            appUrl: '',
            wa_url: '',
            fileUpload: 1,
            userHasLinkedApps: 0,
            caller: '',
            externalApp: null
        }, options),
        request_in_action = false;

    var itemLinkerCache = (function () {
        var cache = [],
            ttl = 100;

        var now = function () {
            return Date.now() / 1000 | 0;
        };

        return {
            get: function (key, f) {
                if (cache[key] && (cache[key]['time'] + ttl > now())) {
                    return cache[key]['value'];
                }
                return null;
            },
            set: function (key, value) {
                cache[key] = {
                    time: now(),
                    value: value
                };
            }
        }
    }());

    var full_itemadd_form = {
        can_show: function () {
            return $.storage && !$.storage.get('pocketlists/item-add-compact-mode');
        },
        set_show: function () {
            return $.storage && $.storage.del('pocketlists/item-add-compact-mode');
        },
        set_hide: function () {
            return $.storage && $.storage.set('pocketlists/item-add-compact-mode', 1);
        }
    };

    /**
     * for new item dom manipulating
     */
    function InitNewItemWrapper($new_item_wrapper) {
        $new_item_wrapper.on('click', '[data-pl2-action="edit-new-item"]', function (e) {
            e.preventDefault();

            openItemDetailsWrapper.call(this, true);

            full_itemadd_form.set_show();
        });

        // $new_item_wrapper.on('open_new_item_wrapper.pl2', function (e) {
        //     e.stopPropagation();

            // if (full_itemadd_form.can_show()) {
                // setTimeout(function() {
                // $(this).find('[data-pl2-action="edit-new-item"]').trigger('click');
                // }, 1000);

                // return;
            // }
        // });

        // var $new_item_wrapper_hover = $('<div class="pl-inner-item-add-placeholder"></div>'),
        var $new_item_wrapper_hover = $('<div id="pl-item-add-wrapper-hover" style="display: none;">'),
            $top_new_item_wrapper = $new_item_wrapper.clone(true),
            $textarea = $new_item_wrapper.find('textarea'),
            $top_textarea = $top_new_item_wrapper.find('textarea');

        var hide_new_item_wrapper = function () {
            $new_item_wrapper.slideUp(200, function () {
                $new_item_wrapper.detach();
                $('.pl-new-item-wrapper').remove();
                $textarea.val('').removeClass('pl-unsaved');
                showEmptyListMessage();

                $(document).trigger('hide_new_item_wrapper.pl2', {
                    add_wrapper: $top_new_item_wrapper
                });
            });
        };

        var init = function () {
            // if (!o.standAloneItemAdd) {
            $new_item_wrapper.detach();
            // }

            var show_new_item_wrapper = function () {
                // hideEmptyListMessage();
                $top_new_item_wrapper.prependTo($undone_items_wrapper).show().wrap('<li data-pl-item-add-top>');
                $(document).trigger('open_new_item_wrapper.pl2', {add_wrapper: $top_new_item_wrapper});

                // if (full_itemadd_form.can_show()) {
                    setTimeout(function () {
                        if (isEmptyList()/* && !o.showMessageOnEmptyList*/) {
                            // $top_textarea.val('').trigger('focus');
                            ItemLinker($top_textarea);
                        }
                    }, 500);
                // }
            };
            !isNewList() && show_new_item_wrapper();

            // handlers for both textareas (top and movable)
            $textarea.add($top_textarea)
                .on('change cut keydown drop paste', function () {
                    window.setTimeout(function () {
                        $.pocketlists.resizeTextarea($textarea)
                    }, 0);
                    window.setTimeout(function () {
                        $.pocketlists.resizeTextarea($top_textarea)
                    }, 0);
                })
                .on('keydown', function (e) {
                    var $this = $(this);
                    if (!o.standAloneItemAdd) {
                        $.pocketlists.enable_prevent_close_browser($this);
                    }
                    $this
                        .data('can_blur', true)
                        .removeClass('pl-unsaved');
                    if (!e.shiftKey && e.which === 13 && !$this.data('pl2-autocomplete-isopen')) {
                        e.preventDefault();
                        if (!o.standAloneItemAdd) {
                            $.pocketlists.disable_prevent_close_browser();
                        }

                        var parent_id = $this.closest('.menu-v').find(item_selector).first().data('parent-id'),
                            name = $this.val().trim();
                        if (name) {
                            addItem.call(this, [{
                                name: name,
                                links: $this.data('pl2-linked-entities'),
                                parent_id: parent_id
                            }]);
                        }
                    } else if (e.which === 27 && !$this.data('pl2-autocomplete-isopen')) {
                        $this.data('can_blur', false);

                        if (!o.standAloneItemAdd) {
                            $.pocketlists.disable_prevent_close_browser();
                            hide_new_item_wrapper();
                        }
                    }


                })
                .on('paste', function () {
                    var self = this,
                        $self = $(self),
                        parent_id = $self.closest('.menu-v').find(item_selector).first().data('parent-id');

                    if (!$self.val().length) {
                        setTimeout(function () {
                            var items = $self.val().split(/\n/),
                                data = [];
                            if (items.length > 1) {
                                for (var i = 0; i < items.length; i++) {
                                    var name = $.trim(items[i]);
                                    if (name) {
                                        data.push({
                                            name: name,
                                            links: [],
                                            parent_id: parent_id
                                        });
                                    }
                                }
                                addItem.call(self, data);
                            }
                        }, 100);
                    }
                })
                .on('focus', function () {
                    var $this = $(this);

                    if (!o.standAloneItemAdd) {
                        $.pocketlists.disable_prevent_close_browser();
                    }

                    if (full_itemadd_form.can_show()) {
                        if (!ItemDetails.isVisible()) {
                            $top_new_item_wrapper.find('[data-pl2-action="edit-new-item"]').trigger('click');
                        }
                    } else {
                        $this
                            .data('can_blur', true)
                            .removeClass('pl-unsaved');
                    }
                })
                .on('blur', function () {
                    var $this = $(this),
                        name = $this.val().trim(),
                        can_blur = $this.data('can_blur');

                    if (can_blur) {
                        if (name) {
                            if (!o.standAloneItemAdd) {
                                $.pocketlists.enable_prevent_close_browser($this);
                            }
                            $this.addClass('pl-unsaved');
                        } else {
                            if (!o.standAloneItemAdd) {
                                $.pocketlists.disable_prevent_close_browser();
                            }
                            hide_new_item_wrapper();
                        }
                    }
                });

            //$wrapper.on('hide.pl2', hide_new_item_wrapper);
            if (o.enableAddLinkOnHover) {
                $undone_items_wrapper.on('click', '.pl-inner-item-add-placeholder',function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // if item has children - place it before first
                    var $this = $(this);
                    var $has_children = $this.closest(item_selector).find('.menu-v');

                    $textarea.data('can_blur', false);
                    if ($has_children.length) { // if item has children - indent
                        $has_children.find('.pl-item').first().before($new_item_wrapper);
                    } else { // else on same level
                        $this.closest(item_selector).find('.pl-item').first().after($new_item_wrapper);
                    }
                    $new_item_wrapper_hover.detach();
                    $new_item_wrapper.slideDown(200);
                    // $new_item_wrapper.trigger('open_new_item_wrapper.pl2');
                    $textarea.focus();

                    $(document).trigger('open_new_item_wrapper.pl2', {
                        add_wrapper: $new_item_wrapper,
                        position: $this.closest(item_selector)
                    });
                });
            }

            ItemLinker($textarea);
            ItemLinker($top_textarea);
        };

        init();

        return {
            hide: hide_new_item_wrapper,
            new_item: {
                wrapper: $new_item_wrapper,
                textarea: $textarea
            },
            top_new_item: {
                wrapper: $top_new_item_wrapper.closest('[data-pl-item-add-top]'),
                textarea: $top_textarea
            }
        }
    }

    /**
     * for item details
     * - show/hide (p)
     * - change details
     */
    function InitItemDetails($wrapper) {
        var itemId = 0,
            $currentItem = null,
            serializedForm = '';

        var showLoading = function ($item) {
            // if (itemId) {
            //     $item.find('.pl-edit').addClass('pl-non-opacity').html($.pocketlists.$loading);
            // } else {
            //     $item.find('[data-pl2-action="edit-new-item"] .ellipsis').toggleClass('pl ellipsis loading')
            // }
        };

        var isOpen = function () {
            return $currentItem !== null;
        };

        var setItem = function ($item) {
            $currentItem = $item;
            serializedForm = '';

            itemId = $currentItem ? parseInt($item.data('id')) || 0 : 0;
        };

        var hideItemDetails = function (e, callback) {
            if ($currentItem) {
                var $addItemTextarea = $currentItem.find('[data-pl2-item-textarea]');

                if (!itemId) {
                    $currentItem.find('[data-pl2-item-links]').show();
                    $addItemTextarea.val($wrapper.find('[name="item[name]"]').val());

                    $addItemTextarea.data('pl2-linked-entities', $wrapper.find('[name="item[name]"]').data('pl2-linked-entities'));
                    // $addItemTextarea.trigger('renderEntities.pl2');
                    ItemLinker($addItemTextarea);
                }

                // $wrapper.slideToggle(0, function () {
                $wrapper.hide().empty().detach();
                // });

                var $selectLabel = $currentItem.find('.pl-item__row');
                if (!$selectLabel.is(':visible')) {
                    $selectLabel.show();
                }

                // $currentItem
                //     .find('.pl-meta')
                //     .css({'position': 'absolute'})
                //     .show()
                //     .animate({'opacity': '1'}, 0, function () {
                //         $(this).css({'position': 'relative'})
                //     })
                //     .end()
                //     .find('.pl-edit')
                //     .removeClass('pl-non-opacity')
                //     .html('<i class="icon16 pl ellipsis"></i>');

                setItem(null);
                $addItemTextarea.closest('[data-pl-item-add]').trigger('itemDetailsClosed.pl2', { details_wrapper: $addItemTextarea.closest('[data-pl-item-add]') });
            }

            if ($.isFunction(callback)) {
                callback.call();
            }

            // $list_items_wrapper.trigger('deselectItem.pl2');
        };

        var listIsVisible = function () {
            return o.list && o.list.list_details.isVisible();
        };

        var showItemDetails = function (e, $item, callback) {
            if (request_in_action) {
                return;
            }

            if ($item === undefined) {
                return;
            }

            if ($item.data('pl2-open-details-in-progress')) {
                return;
            }

            request_in_action = true;
            $item.data('pl2-open-details-in-progress', 1);

            var loadDetails = function () {
                setItem($item);

                //$wrapper.html($.pocketlists.$loading).show();
                // $(window).scrollTop();
                // if (listIsVisible()) {
                //     o.list.list_details.$el.after($wrapper);
                // }

                showLoading($currentItem);

                $.post(o.appUrl + '?module=item&action=details', {
                    id: itemId,
                    list_id: o.list && o.list.list_id ? o.list.list_id : 0,
                    assign_user_id: o.assignUser || 0,
                    caller: o.caller,
                    external_app: o.externalApp
                }, function (html) {
                    $wrapper
                        .html(html)
                        .show();

                    var labelH = $item.find('.pl-select-label').height();

                    $wrapper
                        .css({'max-height': labelH + 18 + 'px'}, 200)
                        .animate({'max-height': 999 + 'px'}, 200)
                        .animate({}, 200, function () {
                            if (listIsVisible()) {
                                o.list.list_details.trigger('hide.pl2');
                            }

                            $.pocketlists.stickyDetailsSidebar();
                        });

                    afterLoad();

                    request_in_action = false;

                    if ($.isFunction(callback)) {
                        callback.apply();
                    }

                    $.pocketlists.flexHack();

                    $item.removeData('pl2-open-details-in-progress');

                    $wrapper.trigger('itemDetailsOpened.pl2', {details_wrapper: $wrapper});
                });
            };

            if (isOpen()) {
                hideItemDetails();
            }

            loadDetails();
        };

        var afterLoad = function () {
            var initDatepicker = function () {
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
                    dateFormat: "yy-mm-dd",
                    onClose: function () {
                        if ($wrapper.find('#pl-item-due-datetime').val()) {
                            if (!$wrapper.find('#pl-item-due-datetime-clear').is(':visible')) {
                                $wrapper.find('#pl-item-due-datetime-set').show();
                            }
                        } else {
                            $wrapper.find('#pl-item-due-datetime-set, #pl-item-due-datetime-hours, #pl-item-due-datetime-minutes, #pl-item-due-datetime-clear').hide()
                        }
                    },
                    beforeShow: function() {
                        var $this = $(this),
                            dp = $this.datepicker('widget');

                        setTimeout(function () {
                            dp.css('z-index', 100);
                        }, 0);
                    }
                };

                $wrapper.find('#pl-item-due-datetime').datepicker(datepicker_options);
            };

            initDatepicker();

            var initFileUpload = function () {
                $wrapper.find('[data-pl-item-details-fileupload]').fileupload({
                    url: o.appUrl + '?module=item&action=addAttachment',
                    dataType: 'json',
                    autoUpload: true,
                    dropZone: '[data-pl-item-details-fileupload]',
                    formData: {
                        item_id: itemId
                    },
                    done: function (e, data) {
                        var $attachments = $wrapper.find('[data-pl-item-details-attachments]'),
                            $list = $attachments.find('ul'),
                            files = [],
                            $files = $wrapper.find('[name="item[files]"]');
                        if (data.result.errors) {
                            alert(data.result.errors[0]);
                            return;
                        }
                        $.each(data.result.data.files, function (index, file) {
                            if (file.id < 0) {
                                $list.append('<li>' + file.name + '</li>');
                                files.push(file.name);
                            } else {
                                $list.append('<li><span class="item">' +
                                    '<a href="' + file.url + '" target="_blank">' + file.name + '</a> ' +
                                    '<a href="#" class="count small gray" data-pl-attachment-id="' + file.id + '" ' +
                                    'data-pl-attachment-name="' + file.name + '" style="margin-left: 10px;" ' +
                                    'title="' + $_('Delete') + '" style="margin-left: 10px;"><i class="fas fa-trash-alt"></i></a>' +
                                    '</span></li>');
                            }
                        });
                        if (files.length) {
                            if (!$files.length) {
                                $list.append('<input type="hidden" name="item[files]" value="' + files.join('|~|') + '"/>');
                            } else {
                                files.push($files.val());
                                $files.val(files.join('|~|'));
                            }
                        }
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $wrapper.find('#progress .progress-bar').css(
                            'width',
                            progress + '%'
                        );
                    }
                });
            };

            if (o.fileUpload) {
                if (!$.fn.fileupload) {
                    $.when(
                        $.getScript(o.wa_url + "wa-content/js/jquery-plugins/fileupload/jquery.iframe-transport.js"),
                        $.getScript(o.wa_url + "wa-content/js/jquery-plugins/fileupload/jquery.fileupload.js"),
                        $.Deferred(function (deferred) {
                            $(deferred.resolve);
                        })
                    ).done(function () {
                        initFileUpload();
                    });
                } else {
                    initFileUpload();
                }
            }

            serializedForm = $wrapper.find('form').serialize();

            $wrapper.find('textarea').trigger('change');

            var $name = $wrapper.find('[name="item[name]"]');

            $name.trigger('focus');
            var nameValue = $name.val();

            if (!itemId) {
                nameValue = $currentItem.find('[data-pl2-item-textarea]').val();
                $currentItem
                    .find('[data-pl2-action="edit-new-item"] .loading').toggleClass('pl ellipsis loading')
                    .end()
                    .find('[data-pl2-item-links]').hide();
            }

            $name.val('').val(nameValue);

            $wrapper.find('#pl-assigned-contact select').trigger('change');

            $name.data('pl2-linked-entities', $currentItem.find('[data-pl2-item-textarea]').data('pl2-linked-entities'));

            ItemLinker($name);
        };

        var init = function () {
            if ($wrapper.data('pl-ItemDetails')) {
                return;
            }

            $wrapper
                .data('pl-ItemDetails', true)
                .on('show.pl2', showItemDetails)
                .on('hide.pl2', hideItemDetails)
                .on('click', '[data-pl2-action="close"]', function (e) {
                    e.preventDefault();

                    hideItemDetails(e, function () {
                        full_itemadd_form.set_hide();
                    });
                })
                .on('submit', 'form', function () {
                    // e.preventDefault();
                    var $form = $(this),
                        $name = $form.find('[name="item[name]"]'),
                        links = $name.data('pl2-linked-entities'),
                        links_delete = $name.data('pl2-linked-entities-delete')
                    ;

                    //$form.find('#pl-item-details-save').after($.pocketlists.$loading);
                    if (itemId) {
                        if (links) {
                            var link_i = 0;
                            for (var linked in links) {
                                for (var key in links[linked]['model']) {
                                    $form.append($('<input name="item[links]['+link_i+'][model]['+key+']" type="hidden">').val(links[linked]['model'][key]));
                                }
                                link_i++;
                            }
                        }

                        $name.removeData('pl2-linked-entities');

                        if (links_delete) {
                            var link_i = 0;
                            for (var linked_del_id in links_delete) {
                                $form.append($('<input name="item[links_delete][' + link_i++ + ']" type="hidden">').val(linked_del_id));
                            }
                        }

                        $name.removeData('pl2-linked-entities-delete');

                        updateItem($form);
                    } else {
                        var formValues = JSON.parse(JSON.stringify($form.serializeArray())),
                            data = {};

                        $.each(formValues, function () {
                            data[this.name.replace('item[','').replace(']','')] = this.value;
                        });

                        if (links) {
                            data['links'] = links;
                        }

                        addItem.call($currentItem.find('[data-pl2-item-textarea]').get(0), [data], function () {
                            $form.trigger('reset');
                            $currentItem.find('[data-pl2-item-textarea]').removeData('pl2-linked-entities');
                            $name.removeData('pl2-linked-entities');
                            hideItemDetails();
                        });

                        return false;
                    }
                })
                .on('click', '.pl-item-details-cancel', function (e) {
                    e.preventDefault();

                    hideItemDetails();
                })
                .on('click', '#pl-item-priority a', function (e) {
                    e.preventDefault();

                    $wrapper
                        .find('#pl-item-priority input')
                        .val($(this).data('pl-item-priority'))
                        .trigger('change');

                    $(this)
                        .addClass('selected')
                        .siblings()
                        .removeClass('selected')
                })
                .on('click', '#pl-item-due-datetime-set', function (e) {
                    e.preventDefault();

                    $(this)
                        .hide()
                        .siblings()
                        .show()
                        .filter('select')
                        .prop('disabled', false);
                })
                .on('click', '#pl-item-due-datetime-clear', function (e) {
                    e.preventDefault();

                    $(this)
                        .hide()
                        .siblings()
                        .show()
                        .filter('select')
                        .hide()
                        .prop('disabled', true);
                })
                .on('click', '[data-pl-action="item-delete"]', function (e) {
                    e.preventDefault();
                    var $dialog_confirm = $('#pl-dialog-delete-item-confirm');

                    if ($dialog_confirm.hasClass('dialog')) {
                        $('body').append($dialog_confirm.show());
                    } else {
                        $.waDialog({
                            content: $dialog_confirm.find('.dialog-content').html(),
                            footer: $dialog_confirm.find('.dialog-buttons').html(),
                            onOpen: function ($dialog, dialog_instance) {

                                $dialog
                                .on('click', '.cancel', function(e) {
                                    e.preventDefault();
                                    dialog_instance.close();
                                })
                                .on('click', '[type="submit"]', function(e) {
                                    e.preventDefault();

                                    if (request_in_action) {
                                        return;
                                    }
                                    request_in_action = true;

                                    $.post(o.appUrl + '?module=item&action=delete', {id: itemId}, function (r) {
                                        if (r.status === 'ok') {
                                            if (!o.standAloneItemAdd) {
                                                if (o.list) {
                                                    loadListCounts(o.list.list_id);
                                                }
                                            }

                                            dialog_instance.close();
                                            hideItemDetails(null, function () {
                                                removeItem(r.data.id);
                                                $list_items_wrapper.find('[data-id="' + r.data.id + '"]').remove();
                                                if (o.list) {
                                                    loadListCounts(o.list.list_id);
                                                }
                                            });

                                            $(document).trigger('item_delete.pl2', r.data);
                                        } else {

                                        }
                                        request_in_action = false;
                                    }, 'json');
                                    return false;

                                })
                            }
                        });
                    }
                })
                .on('change', '#pl-assigned-contact select', function () {
                    var assigned_contact_id = parseInt($(this).val());

                    if (assigned_contact_id && assigned_contact_id != o.current_user_id) {
                        $wrapper.find('#pl-assigned-contact [data-pl-contact-id="' + assigned_contact_id + '"]')
                            .show()
                            .siblings()
                            .hide();
                        $wrapper.find('[data-pl2-item-assign-hint]').show();
                    } else {
                        $wrapper.find('#pl-assigned-contact [data-pl-contact-id]').hide();
                        $wrapper.find('[data-pl2-item-assign-hint]').hide();
                    }
                })
                .on('change paste keyup', ':input', function () {
                    $wrapper.find('#pl-item-details-save')
                        .removeClass('yellow green')
                        .addClass($wrapper.find('form').serialize() !== serializedForm ? 'yellow' : 'green');
                })
                .on('change', '#pl-item-pocket', function () {
                    if (request_in_action) {
                        return;
                    }
                    request_in_action = true;

                    $(this).after($.pocketlists.$loading);
                    $.get(o.appUrl + '?module=backendJson&action=getLists', function (r) {
                        $.pocketlists.$loading.remove();
                        var $pocket_lists = $('#pl-item-list');
                        $pocket_lists.empty();
                        if (r.status === 'ok') {
                            $.each(r.data, function () {
                                $pocket_lists.append($('<option value="' + this.id + '">').text(this.name));
                            });
                        } else {
                            $pocket_lists.append('<option value="" selected="selected">' + $_('None') + '</option>');
                        }
                        $pocket_lists.trigger('change');
                        request_in_action = false;
                    }, 'json');
                })
                .on('change', '#pl-item-list', function () {
                    var item_id = $(this).find(':selected').val();
                    $wrapper.find('input[name="item\[list_id\]"]').val(item_id);
                    if (item_id) {
                        $(this).show();
                        $wrapper.find('#pl-null-list-msg').hide();
                    } else {
                        $(this).hide();
                        $wrapper.find('#pl-null-list-msg').show();
                    }
                })
                .on('click', '[data-pl-attachment-id]', function (e) {
                    e.preventDefault();

                    if (!confirm($_('Are you sure you want to delete this file?'))) {
                        return false;
                    }

                    var $this = $(this),
                        attachment_id = $this.data('pl-attachment-id'),
                        $w = $this.closest('li');

                    $.post(o.appUrl + '?module=item&action=deleteAttachment', {
                        attachment: attachment_id,
                        item_id: itemId
                    }, function (r) {
                        if (r.status === 'ok') {
                            $w.hide(200, function () {
                                $w.remove();
                            });
                        }
                    }, 'json');
                })
                .on('change cut keydown drop paste', 'textarea', function (e) {
                    var $textarea = $(this),
                        keycode = (e.keyCode ? e.keyCode : e.which);

                    if(keycode == '13' && !e.shiftKey && !$textarea.is('[name="item[note]"]')) {
                        $textarea.closest('form').trigger('submit');
                        e.preventDefault();
                    } else {
                        window.setTimeout(function () {
                            $.pocketlists.resizeTextarea($textarea);
                        }, 0);
                    }
                })
                .on('click', '[data-pl2-link-action="delete"]', function (e) {
                    e.preventDefault();

                    var $this = $(this),
                        $previewWrapper = $this.closest('[data-pl2-link-id]'),
                        link_id = $previewWrapper.data('pl2-link-id'),
                        link_hash = $previewWrapper.data('pl2-link-hash'),
                        $name = $wrapper.find('[name="item[name]"]'),
                        links = $name.data('pl2-linked-entities') || {},
                        links_to_delete = $name.data('pl2-linked-entities-delete') || {};

                    $previewWrapper.remove();

                    if (link_id) {
                        links_to_delete[link_id] = link_id;
                        $name.data('pl2-linked-entities-delete', links_to_delete);
                    }

                    if (links[link_hash] !== undefined) {
                        delete links[link_hash];
                        $name.data('pl2-linked-entities', links);
                    }
                })
            ;

            return this;
        };

        init();

        return {
            $el: $wrapper,
            trigger: function (event, data) {
                this.$el.trigger(event, data);
            },
            isVisible: function () {
                return this.$el.children().length;
            }
        };
    }

    var ItemDetails = InitItemDetails($('#pl2-item-details'));
    var NewItemWrapper = InitNewItemWrapper($('[data-pl-item-add]'));
        // todo: add wrapper around $.post $.get with request_in_action implementation

    // sortable items
    function initSortable() {
        if (o.enableSortItems) {
            $sortable_items.sortable({
                draggable: item_selector,
                delay: 200,
                delayOnTouchOnly: true,
                animation: 150,
                forceFallback: true,
                ghostClass:'pl-item-placeholder',
                // chosenClass:'album-list-chosen',
                // dragClass:'album-list-drag',
                onEnd: function(event) {
                    let $item = $(event.item);
                    /* хак для предотвращения срабатывания клика по элементу после его перетаскивания*/
                    let $link = $item.find('[onclick]'),
                        href = $link.attr('onclick');
                    $link.attr('onclick', 'javascript:void(0);');
                    setTimeout(() => $link.attr('onclick', href),500)

                    if ($item.hasClass('pl-dropped')) {
                        $(this).sortable('cancel');
                        $item.removeClass('pl-dropped');
                    } else {
                        var $prev = $item.parents(item_selector).first(),
                            parent_id = $prev.length ? parseInt($prev.data('id')) : 0;

                        $item.data('parent-id', parent_id);
                        updateSort(parseInt($item.data('id')));
                    }
                }
            });
        } else {
            if (!o.standAloneItemAdd) {
                $(item_selector, $sortable_items).draggable({
                    handle: '[data-pl-action="item-sort"]',
                    distance: 5,
                    opacity: 0.75,
                    appendTo: 'body',
                    // connectWith: '[data-pl-items="done"] ul.menu-v',
                    tolerance: 'pointer',
                    revert: true,
                    revertDuration: 0,
                    classes: {
                        'ui-sortable-helper': 'shadowed'
                    }
                    // forcePlaceholderSize: true,
                    // forceHelperSize: true,
                });
            }
        }
    }
    // save item
    function addItem(data, callback) {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        var $this = $(this),
            isTopAdd = $this.closest('[data-pl-item-add-top]').length;

        if (o.dueDate) {
            $.each(data, function (i) {
                data[i]['due_date'] = o.dueDate;
            });
        }

        if (o.defaultLinkedEntity !== false) {
            $.each(data, function (i) {
                if (data[i]['links'] === undefined) {
                    data[i]['links'] = [{model: o.defaultLinkedEntity}];
                } else {
                    data[i]['links']['defaultLinkedEntity'] = {model: o.defaultLinkedEntity};
                }
            });
        }

        var $pl_done = $this.closest('.pl-item').find('.pl-done-label span').addClass('transparent').html($.pocketlists.$loading);

        var itemData = {
                list_id: o.list ? o.list.list_id : 0,
                data: data,
                assigned_contact_id: o.assignUser ? o.assignUser : false,
                filter: o.filter,
                external_app: o.externalApp
            },
            eventData = {
                response: itemData,
                wrapper: $this,
                isTopAdd: isTopAdd
            };

        $(document)
            .trigger('beforeAddItemAsync.pl2', eventData)
            .triggerHandler('beforeAddItemSync.pl2', eventData);

        $.post(
            o.appUrl + '?module=item&action=create',
            eventData.response,
            function (html) {
                request_in_action = false;

                if (!o.standAloneItemAdd) {
                    $.pocketlists.updateAppCounter();
                    $.pocketlists.reloadSidebar();
                }
                $.pocketlists.$loading.remove();

                $pl_done.removeClass('transparent');

                var $li = $this.closest(item_selector),
                    $html = $('' + html + ''),
                    _itemAdd = isTopAdd ? NewItemWrapper.top_new_item : NewItemWrapper.new_item,
                    due_date = $html.data('pl-due-date');

                if (!o.enableSortItems) {
                    $html.find('[data-pl-action="item-sort"]').hide();
                }

                _itemAdd.textarea.removeData('pl2-linked-entities');
                _itemAdd.textarea.removeData('pl2-linked-entities-delete');

                _itemAdd.textarea.data('can_blur', false);
                if ($li.length) {
                    if (!$li.parents(item_selector).length || // not root item
                        //$li.prev(item_selector).length || // not first item in subs
                        _itemAdd.wrapper.prev('.pl-item').length) { // new item wrapper is after item
                        $li.after($html);
                    } else {
                        $li.before($html);
                    }
                } else { // added to top
                    _itemAdd.wrapper.after($html);
                }
                $html.filter(item_selector).last()
                    .find('.pl-item').first().after(_itemAdd);

                _itemAdd.textarea.val('').css('height', 'auto').data('can_blur', true);

                if (!full_itemadd_form.can_show()) { // do not focus on full add mode
                    setTimeout(function () {
                        _itemAdd.textarea.trigger('focus');
                    }, 500);
                }

                // update calendar date with new dot
                var $calendar = $('.pl-calendar');
                if (!o.list && $calendar.length && !o.standAloneItemAdd) {
                    $.get(o.appUrl + '?module=backendJson&action=getItemsPocketColor&id=' + parseInt($html.data('id')), function (r) {
                        if (r.status === 'ok') {
                            var $selected_date = $calendar.find('[data-pl-todo-date="' + due_date + '"]').length
                                ? $calendar.find('[data-pl-todo-date="' + due_date + '"]')
                                : false;
                            if ($selected_date) {
                                var $dots_wrapper = $selected_date.find('.pl-dots'),
                                    $new_dot = $('<i class="icon10 color pl-dark-gray">');

                                if (!$dots_wrapper.length) {
                                    $dots_wrapper = $('<div class="pl-dots">');
                                    $selected_date.append($dots_wrapper);
                                }

                                if ($dots_wrapper.find('i').not('.pl-dark-none').length < 3) {
                                    $dots_wrapper.append($new_dot);
                                }
                            }
                        }
                    }, 'json');
                }

                var $textarea_parent = _itemAdd.textarea.closest('[data-pl-item-add]') || _itemAdd.textarea.closest(item_selector),
                    $previewWrapper = $textarea_parent.find('[data-pl2-item-links]');

                $previewWrapper.empty();

                if (!o.standAloneItemAdd) {
                    hideEmptyListMessage();
                    updateSort();
                    if (o.list) {
                        loadListCounts(o.list.list_id);
                    }
                }

                // $.pocketlists.sendNotifications(o.appUrl);

                $.isFunction(callback) && callback.call($this);

                $(document).trigger('item_add.pl2', { add_wrapper: $textarea_parent });
            }
        );
    }

    function loadListCounts(list_id) {
        updateListCountBadge();
        $.getJSON(o.appUrl + '?module=backendJson&action=getListItemCount', {id: list_id}, function(r) {
            if (r.status === 'ok') {
                var $list = $('#pl-lists').find('[data-pl-list-id="' + list_id + '"]');

                $list.find('[data-pl2-list-items]').text(r.data.count);
                $list.find('[data-pl2-list-calc-priority]').removeClass().hide();

                if (r.data.count && r.data.max_priority) {
                    $list.find('[data-pl2-list-calc-priority]')
                        .addClass('badge ' + r.data.class)
                        .text(r.data.count_max_priority)
                        .show();
                }
            }
        });
    }

    // update item
    function updateItem($form, callback) {
        var item_id = $form.find('#pl-item-list-id').val(),
            item_list_id = $form.find('#pl-item-list-id').val(),
            item_list_id_new = $form.find('[name="item\[list_id\]"]').val();

        var afterUpdateItem = function (html, callback) {
            if (!o.standAloneItemAdd) {
                $.pocketlists.updateAppCounter();
                $.pocketlists.reloadSidebar();
            }

            $.pocketlists.$loading.remove();
            replaceItem(html);
            // update indicator color
            if (o.list) {
                loadListCounts(item_list_id);

                if (item_list_id != item_list_id_new) {
                    loadListCounts(item_list_id_new);
                    removeItem($form.find('input[name="item\[id\]"]').val());
                }

            }
            $(document).trigger('item_update.pl2');
            $.isFunction(callback) && callback.call();
        };

        var _iframePost = function ($form, callback) {
            if (request_in_action) {
                return;
            }
            request_in_action = true;


            var form_id = $form.attr('id'),
                iframe_id = form_id + '-iframe';

            // add hidden iframe if need
            if (!$('#' + iframe_id).length) {
                $form.after("<iframe id=" + iframe_id + " name=" + iframe_id + " style='display:none;'></iframe>");
            }

            var $iframe = $('#' + iframe_id);
            $form.attr('target', iframe_id);

            $(document)
                .trigger('beforeUpdateItemAsync.pl2', $form)
                .triggerHandler('beforeUpdateItemSync.pl2', $form);

            $iframe.one('load', function () {
                var html = $(this).contents().find('body').html();
                afterUpdateItem(html, callback);

                request_in_action = false;
            });
        };
        //var _ajaxPost = function ($form, callback) {
        //    $.post('?module=item&action=data', $form.serialize(), function (html) {
        //        afterUpdateItem(html, callback);
        //    });
        //};

        _iframePost($form, callback);
    }
    // get all items list
    function getItems($items_wrapper) {
        $items_wrapper = $items_wrapper || $undone_items_wrapper;
        var data = [];
        $items_wrapper.find(item_selector).each(function (i) {
            var $this = $(this),
                $pldone = $this.find('.pl-done'),
                priority = $pldone.length ? parseInt($pldone.data('pl2-item-priority')) : 0;

            data.push({
                id: $this.data('id'),
                parent_id: $this.data('parent-id'),
                sort: i,
                has_children: $this.find(item_selector).length ? 1 : 0,
                priority: priority
            });
        });
        return data;
    }
    // update sort base on current positions
    function updateSort(id) {
        //this.find('label').first().append($.pocketlists.$loading);
        if (o.enableSortItems && o.list) {
            if (request_in_action) {
                return;
            }
            request_in_action = true;

            $.post(
                o.appUrl + '?module=item&action=sort',
                {
                    list_id: o.list.list_id,
                    data: getItems()
                },
                function (r) {
                    if (r.status === 'ok') {
                        initSortable();
                    } else {
                        alert(r.errors);
                    }
                    //$.pocketlists.$loading.remove();
                    request_in_action = false;
                },
                'json'
            );
        }
    }
    // complete/uncomplete items
    function completeItem($item, status, callback) {
        var id = parseInt($item.data('id')),
            $assigned_user_icon = $item.find('.pl-done-label').find('.icon16.userpic20'),
            $item_data_wrapper = $item.find('.pl-item'),
            $checkbox = $(this);

        if (status && $item_data_wrapper.data('pl-assigned-contact') && $item_data_wrapper.data('pl-assigned-contact') != o.current_user_id) {
            if (!confirm($_('This to-do is assigned to another person. Are you sure you want to mark this item as complete?'))) {
                $checkbox.prop('checked', false); // uncheck
                callback && $.isFunction(callback) && callback.call($item);
                return;
            }
        }

        $item_data_wrapper.toggleClass('gray');
        $assigned_user_icon.hide();
        $.post(
            o.appUrl + '?module=item&action=complete',
            {
                id: id,
                status: status
            },
            function (r) {
                if (r.status === 'ok') {
                    if (!o.standAloneItemAdd) {
                        $.pocketlists.updateAppCounter();
                    }
                    // remove from undone list
                    $checkbox.prop('checked', status); // check nesting items
                    setTimeout(function () {
                        $item.slideToggle(200, function () {
                            $assigned_user_icon.show();

                            if (status) {
                                if ($done_items_wrapper.length) {
                                    $done_items_wrapper.prepend($item);
                                    $item.show();
                                }
                                $item.find('.pl-reply').hide();
                            } else {
                                $undone_items_wrapper.length && $undone_items_wrapper.append($item.show());
                                $item.find('.pl-reply').show();
                                updateSort();
                            }

                            // always update list count icon
                            $show_logbook_items.show().find('i').text($_('Show all %d completed to-dos').replace('%d', $done_items_wrapper.find('[data-id]').length)); // update "complete items" heading

                            if (!o.standAloneItemAdd) {
                                if (o.list) {
                                    loadListCounts(o.list.list_id);
                                }

                                $.pocketlists.reloadSidebar();
                            }

                            showEmptyListMessage();

                            // $.pocketlists.sendNotifications(o.appUrl);

                            callback && $.isFunction(callback) && callback.call($item);

                            $(document).trigger('item_complete.pl2', r.data);
                        });
                    }, 800);

                } else {
                    $assigned_user_icon.show();
                    alert(r.errors);
                }
                $.pocketlists.$loading.remove();
            },
            'json'
        );
    }
    // increase item level
    function increaseItem(e) {
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
    }
    // decrease item level
    function decreaseItem(e) {
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
    }
    // favorite item
    function favoriteItem($item) {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        var $star = $item.find('.pl-favorite'),
            id = parseInt($item.data('id')),
            status = parseInt($star.data('is-favorite'));

        $.post(
            o.appUrl + '?module=item&action=favorite',
            {
                id: id,
                status: status
            },
            function (r) {
                if (r.status === 'ok') {
                    var $favorites_count = $('[data-pl-sidebar="favorites-count"]'),
                        current_favorites_count = parseInt($favorites_count.text()) || 0;

                    // current_favorites_count = current_favorites_count.length && parseInt(current_favorites_count) ? parseInt(current_favorites_count) : 0;
                    if (status && current_favorites_count >= 0) {
                        current_favorites_count++;
                    } else if (!status && current_favorites_count > 0) {
                        current_favorites_count--;
                    }
                    $favorites_count.text(current_favorites_count === 0 ? '' : current_favorites_count);

                    $star.data('is-favorite', 1 - status);
                    if(status === 0) {
                        $star.removeClass('text-yellow').addClass('text-light-gray');
                    } else {
                        $star.removeClass('text-light-gray').addClass('text-yellow');
                    }
                } else {
                    alert(r.errors);
                }
                request_in_action = false;
                //$.pocketlists.$loading.remove();
            },
            'json'
        );
    }
    // select clicked item
    function selectItem($item) {
        $current_item = $item;
        if ($current_item) {
            var $item_data_wrapper = $current_item.find('.pl-item').first();

            $list_items_wrapper.find('.pl-item').removeClass('pl-item-selected'); // remove selected class from all items
            $item_data_wrapper.addClass('pl-item-selected'); // add to currently clicked item
            $current_item.prop('checked', true);
        }
    }
    // deselect clicked item
    function deselectItem() {
        if ($current_item) {
            $list_items_wrapper.find('.pl-item').removeClass('pl-item-selected');
            $current_item.prop('checked', false);
            $current_item = null;
        }
    }
    // remove item row
    function removeItem(id) {
        $list_items_wrapper.find('[data-id="' + id + '"]').remove();
    }
    function replaceItem(data) {
        if ($current_item) {
            var setContent = function () {
                $current_item.find('.pl-item').first().replaceWith($(data).addClass('pl-item-selected'));
            };

            if (ItemDetails.isVisible()) {
                ItemDetails.trigger('hide.pl2', setContent);
            } else {
                setContent();
            }
        }
    }
    function updateListCountBadge() {
        if (o.list && o.list.list_id) {
            $('#pl-lists')
                .find('[data-pl-list-id="' + o.list.list_id + '"]')
                .find('.count [data-pl2-list-items]').text($undone_items_wrapper.find('[data-id]').length);
        }
    }
    function isEmptyList() {
        return getItems().length ? false : true;
    }
    function isNewList() {
        return o.list && o.list.list_id < 0;
    }
    function showEmptyListMessage() {
        if (isEmptyList() && o.showMessageOnEmptyList) {
            $empty_list_msg.show();
            //$('.pl-title h1').css('opacity','0.25');
        }
    }
    function hideEmptyListMessage() {
        if (o.showMessageOnEmptyList) {
            $empty_list_msg.hide();
            //$('.pl-title h1').css('opacity','1');
        }
    }

    function moveToList(list_id, toList) {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        var $this = $(this);

        return $.post(
            o.appUrl + '?module=item&action=moveToList',
            {
                id: parseInt($this.data('id')),
                list_id: list_id
            },
            function (r) {
                // $.pocketlists.$loading.removeAttr('style').remove();
                var $toList = $(toList);
                if (r.status === 'ok') {
                    var item_list_id_new = $toList.data('pl-list-id'),
                        item_list_id = o.list.list_id;

                    loadListCounts(item_list_id);

                    if (item_list_id != item_list_id_new) {
                        loadListCounts(item_list_id_new);
                        $this.hide(200, function () {
                            $this.remove();
                        });
                    }

                    $toList.addClass('pl-drop-success');

                    $(document).trigger('item_move.pl2', r.data);
                } else {
                    $this.addClass('pl-drop-fail');
                }
                // $(drop).trigger('dropActionDone.pl2', {result: r.status === 'ok'});

                setTimeout(function () {
                    $toList.removeClass('pl-drop-success pl-drop-fail');
                    $this.removeClass('pl-drop-fail');
                }, 500);

                request_in_action = false;
            },
            'json'
        );
    }
    function assignTo(team_id, drop) {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        var $this = $(this);

        return $.post(
            o.appUrl + '?module=item&action=assignTo',
            {
                id: parseInt($this.data('id')),
                team_id: team_id
            },
            function (r) {
                // $.pocketlists.$loading.removeAttr('style').remove();
                if (r.status === 'ok') {
                    if ($this.find('.pl-item').data('pl-assigned-contact') === undefined) {
                        $this.find('.pl-item-name').after('<strong class="hint"><br>' + $_('Assigned to') + ' ' + r.data + '</strong>');
                    } else {
                        $this.find('.pl-item-name + .hint').html('<br>' + $_('Assigned to') + ' ' + r.data);
                    }
                    $this.find('.pl-item').data('pl-assigned-contact', team_id);

                    // $.pocketlists.sendNotifications(o.appUrl);
                }
                $(drop).trigger('dropActionDone.pl2', {
                    $obj: $this,
                    result: r.status === 'ok'
                });
                request_in_action = false;
            },
            'json'
        );
    }

    function ItemLinker($textarea) {
        if (!o.userHasLinkedApps) {
            return;
        }

        // if ($textarea.data('pl2-itemlinker')) {
        //     return;
        // }

        var itemText = $textarea.val(),
            $parent = findParent();

        if (!$parent) {
            window.console && console.log('error in ItemLinker parent find');

            return;
        }

        var $previewWrapper = $parent.find('[data-pl2-item-links]'),
            linkedEntities = $textarea.data('pl2-linked-entities');

        function findParent() {
            var $parents = [$textarea.closest('#pl-item-details-form'), $textarea.closest('[data-pl-item-add]'), $textarea.closest(item_selector)],
                $parent = null;

            for (var i = 0; i < $parents.length; i++) {
                if ($parents[i].length) {
                    $parent = $parents[i];

                    break;
                }
            }

            return $parent;
        }

        // $textarea.data('pl2-itemlinker', true);

        var log = function (msg) {
            window.console && console.log('pl2 autocomplete', msg);
        };

        var canShowAutocomplete = function () {
            log('canShowAutocomplete');

            itemText = $textarea.val();

            if (!itemText) {
                log('no text');

                return false;
            }

            if (!/(^| )(@|#|№)/.test(itemText)) {
                log('no @|#|№');

                return false;
            }

            // var cursorPos = doGetCaretPosition($textarea[0]);
            var cursorPos = $textarea.prop("selectionStart");

            var equalTrigger = function(text) {
                return text === '@' || text === '#' || text === '№';
            };

            for (var i = cursorPos; !equalTrigger(itemText[i]) && i >= 0; i--) {
                if (itemText[i] == ' ') {
                    return false;
                }
            }

            for (var j = cursorPos; itemText[j] != ' ' && j < itemText.length; j++) {
            }

            var term = itemText.slice(i + 1, j);

            return term;
        };

        if ($textarea.data('autocomplete')) {
            $textarea.autocomplete('destroy');
            $textarea.removeData('autocomplete')
        }
        $textarea.autocomplete({
            // html:true,
            // autoFocus: true,
            source: function (request, response) {
                var term = canShowAutocomplete();

                if (term === false) {
                    return;
                }

                if (term === '') {
                    return response([{
                        'value': '',
                        'label': '<em>' + $_('Find order or task by ID') + '</em>'
                    }]);
                }

                // Prevent requests if canShowAutocomplete() === false
                if (term === false) {
                    return;
                }

                var plresponse = function (data) {
                    if (data.status != 'ok') {
                        return [];
                    }

                    var results = [];

                    $.each(data.data, function (i, typeResults) {
                        log(typeResults);

                        if (!typeResults) {
                            return;
                        }

                        $.each(typeResults.entities, function (i, entity) {
                            results.push(entity)
                        });
                    });

                    itemLinkerCache.set(term, results);

                    return response(results)
                };

                var results = itemLinkerCache.get(term);

                if (results) {
                    response(results);
                } else {
                    $.getJSON(o.appUrl + '?module=item&action=linkAutocomplete', {
                        term: term,
                        params: {
                            allow_delete: ItemDetails.isVisible()
                        }
                    }, plresponse);
                }
            },
            focus: function () {
                // prevent value inserted on focus
                return false;
            },
            select: function (event, ui) {
                event.preventDefault();
                event.stopPropagation();

                var linked = $textarea.data('pl2-linked-entities') || {},
                    link = ui.item.data,
                    hash = link.model.app + link.model.entity_type + link.model.entity_id;

                if (linked[hash] === undefined) {
                    linked[hash] = link;
                    showLinkedPreview(link);
                    $textarea.data('pl2-linked-entities', linked);
                }

                var term = canShowAutocomplete(),
                    text = $textarea.val(),
                    new_text = text.replace(new RegExp('(@|#|№)' + term), ui.item.value);

                $textarea.val(new_text);

                return false;
            },
            open: function () {
                $textarea.autocomplete('widget').css('z-index', 100);
                return false;
            },
            delay: 300,
        });

        // old jq ui hack
        if ($textarea.data("ui-autocomplete") !== undefined) {
            $textarea.data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li>")
                    .append($(item.label).addClass('ui-menu-item-wrapper'))
                    .appendTo(ul);
            }
        }

        $textarea
            .off('autocompleteopen').on("autocompleteopen", function (event, ui) {
            $textarea.data('pl2-autocomplete-isopen', true);
        })
            .off('autocompleteclose').on("autocompleteclose", function (event, ui) {
            $textarea.data('pl2-autocomplete-isopen', false);
        });

        var showLinkedPreview = function (link) {
            var $linkPreview = $('<div data-pl2-link-preview></div>');

            $linkPreview.html(link.preview);

            $previewWrapper.append($linkPreview).show();
        };

        function renderEntities() {
            if (linkedEntities) {
                $previewWrapper.empty();

                for(var linkedEntity in linkedEntities) {
                    showLinkedPreview(linkedEntities[linkedEntity]);
                }
            }
        }
        renderEntities();
        // $textarea.on('renderEntities.pl2', renderEntities);
    };

    // ох сколько всего накручено =( vue плачет
    function openItemDetailsWrapper(e, brandNew) {
        var $this = $(this),
            $item = null;

        switch (true) {
            case $this.closest('[data-pl-item-add]').length > 0:
                $item = $this.closest('[data-pl-item-add]');
                break;
            // case $this.closest('[data-pl-item-add]').length:
            //     $item = $this.closest('[data-pl-item-add]');
            //     break;
            case $this.closest(item_selector).length > 0:
                $item = $this.closest(item_selector);
                break;
        }

        if ($item.data('pl-complete-datetime')) {
            return;
        }

        ItemDetails.trigger('hide.pl2', function () {
            ItemDetails.$el.appendTo($item.find('[data-pl2-item-details]'));

            ItemDetails.trigger('show.pl2', [$item, function () {
                $item.find('.pl-item__row').hide();
            }]);

            selectItem($item);
        });
    }


    function init() {
        //if ($.pocketlists_routing.getHash() == '#/todo/' &&
        //    $.pocketlists_routing.getHash().indexOf('/team/') > 0) {
        //    $new_item_wrapper.prependTo($undone_items_wrapper).slideDown(200).wrap('<li class="pl-new-item-wrapper">');
        //    $new_item_input.focus();
        //}

        $.store && !$.storage && ($.storage = new $.store());

        var do_not_show_item_details = false;

        if (o.archive) {
            $list_items_wrapper.find(':checkbox').prop('disabled', true);
        }

        showEmptyListMessage();
        initSortable();

        $list_items_wrapper
        /**
         * complete/uncomplete item
         */
            .on('click', '.pl-done', function () {
                var $this = $(this),
                    $item = $this.closest(item_selector),
                    status = $this.prop('checked') ? 1 : 0;

                $this.prop('disabled', true);
                completeItem.call(this, $item, status, function () {

                    if (ItemDetails.isVisible()) {
                        ItemDetails.trigger('hide.pl2');
                    }
                    $this.prop('disabled', false);
                });
            })
            /**
             * select/deselect item
             */
            // todo: do we really need checkbox?
            .on('change', '.pl-is-selected', function (e) {
                var $this = $(this),
                    $item = $this.closest(item_selector),
                    is_selected = ($current_item && $current_item.data('id') == $item.data('id')) ? true : false,
                    item_id = parseInt($item.data('id'));

                e.preventDefault();

                if (item_id) {
                    if (!ItemDetails.isVisible() && !is_selected) { // on first click - select
                        ItemDetails.trigger('hide.pl2');
                        selectItem($item);
                    } else { // on third
                        // ItemDetails.trigger('hide.pl2');
                        // deselectItem();
                    }
                }
            }) // action: select item
            // .on('click', '.pl-edit', function (e) {
            //     e.preventDefault();
            //
            //     var $this = $(this),
            //         $item = $this.closest(item_selector);
            //
            //     ItemDetails.trigger('show.pl2', [parseInt($item.data('id'))]); // show item details
            //     selectItem($item);
            // })
            .on('click', '[data-pl-action="item-favorite"]', function (e) {
                e.preventDefault();
                var $this = $(this),
                    $item = $this.closest(item_selector);

                favoriteItem($item);
            }) // action: favorite item`
            .on('increaseSelectedItem.pl2', increaseItem)
            .on('decreaseSelectedItem.pl2', decreaseItem)
            .on('deselectItem.pl2', deselectItem)
            .on('removeItem.pl2', function (e, id) {
                removeItem(id);
            })
            .on('replaceSelectedItem.pl2', function (e, data) {
                replaceItem(data);
            })
            .on('change cut keydown drop paste', '.pl-chat .pl-reply textarea', function () {
                var $textarea = $(this);
                window.setTimeout(function () {
                    $.pocketlists.resizeTextarea($textarea)
                }, 0);
            })
            /* calendar day highlight */
            .on('mouseenter mouseleave', '.pl-item-wrapper[data-pl-due-date]', function () {
                var $calendar = $('.pl-calendar');
                if ($calendar.length) {
                    var $day = $calendar.find('[data-pl-todo-date="' + $(this).data('pl-due-date') + '"]');
                    if ($day.length) {
                        $day.toggleClass('highlighted-background');
                    }
                }
            })
            .on('moveToList.pl2', item_selector, function (e, data) {
                moveToList.call(this, data.id, data.drop);
            })
            .on('assignTo.pl2', item_selector, function (e, data) {
                assignTo.call(this, data.id, data.drop);
            })
            .on('dblclick', '.pl-select-label', function (e) {
                e.preventDefault();

                openItemDetailsWrapper.call(this);
            })
            .on('click', '.pl-edit', function (e) {
                e.preventDefault();

                openItemDetailsWrapper.call(this);
            })
            .on('click', '.pl-comment', function (e) {
                e.preventDefault();
                var $item = $(this).closest('.pl-item-wrapper[data-id]');

                $item.find('.pl-item-discussion').toggle().find('.pl-reply textarea').trigger('focus');
            })
        ;

        $(document)
            .on('openItemDetails.pl2', function (e, data) {
                e.preventDefault();

                openItemDetailsWrapper.call(data.pledit);
            })
        ;

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
                    //($current_item.length && $current_item.find('.pl-chat').is(':visible')) && hideChatCommentInput();
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
        trigger: function (event, data) {
            this.$el && this.$el.trigger(event, data);
        }
    };
};
