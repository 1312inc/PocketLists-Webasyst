"use strict";

/**
 * object to work with list
 * - add
 * - print
 * - delete
 * - archive
 * - sort all
 */
$.pocketlists.List = function ($list_wrapper, options) {
    var $new_list_input = $list_wrapper.find('#pl-new-list-input'),
        $uho = $('[data-pl="uho"]'),
        list_id = parseInt($list_wrapper.find('#pl-list-id').val()),
        pocket_id = parseInt($list_wrapper.find('input[name="pocket_id"]').val()),
        o = $.extend({}, {
            archive: false,
            totalItems: 0
        }, options),
        request_in_action = false;

    /**
     * for show and manipulate with list details
     * - show/hide container (p)
     * - change icon
     * - save details
     */
    var ListDetails = (function ($wrapper) {
        var icon_path = null,
            list_color = 'none';

        var getListColor = function () {
            return $wrapper.find('#pl-list-color a.selected').data('pl-list-color');
        };
        var setListColor = function (color) {
            color = color || list_color;
            //$('.pl-items').removeClass().addClass('pl-items pl-' + color);
            $('#pl-list-name').removeClass().addClass('pl-items pl-dark-' + color + '-label');
            $('.pl-item-wrapper .pl-done-label span').removeClass().addClass('pl-items pl-dark-' + color + '-border');

        };
        // show list details container and load html with list details from server
        var showListDetails = function () {
            if (request_in_action) {
                return;
            }
            request_in_action = true;

            $.pocketlists.scrollToTop(200, 80);

            var $itemDetails = $('#pl-item-details');
            $itemDetails.length && $itemDetails.after($wrapper);
            $wrapper.html($.pocketlists.$loading).show().animate({
                'right': '0'
            }, 200, function () {
                $itemDetails.length && $itemDetails.trigger('hide.pl2');
                $.pocketlists.stickyDetailsSidebar();
            });

            $.post('?module=list&action=' + $wrapper.data('pl2-list-edit-action'), {id: list_id}, function (html) {
                $wrapper.html(html);
                afterLoad();
            }).always(function () {
                request_in_action = false;
            });
        };
        var hideListDetails = function () {
            setListColor();
            $wrapper.animate({
                'right': '-300px'
            }, 200, function () {
                $wrapper.hide().empty()
            });
        };
        var updateList = function (data) {
            $list_wrapper.find('#pl-list-name').text(data.name); // update name
            if (data.due_date || data.due_datetime) {
                var due_class = 'pl-due-someday';
                if (data.calc_priority == 1) {
                    due_class = 'pl-due-tomorrow';
                } else if (data.calc_priority == 2) {
                    due_class = 'pl-due-today';
                } else if (data.calc_priority == 3) {
                    due_class = 'pl-due-overdue';
                }
                $list_wrapper
                    .find('.pl-list-due[data-pl-action="list-edit"]')
                    .show()
                    .find('[data-pl-list="due-date"]')
                    .removeClass()
                    .addClass('bold ' + due_class)
                    .text(data.due_datetime ? data.due_datetime : data.due_date);
                $list_wrapper.find('.inline-link[data-pl-action="list-edit"]').hide();
            } else {
                $list_wrapper.find('.pl-list-due[data-pl-action="list-edit"]').hide();
                $list_wrapper.find('.inline-link[data-pl-action="list-edit"]').show();
            }
        };
        var afterLoad = function () {
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
                    if ($wrapper.find('#pl-list-due-datetime').val()) {
                        if (!$wrapper.find('#pl-list-due-datetime-clear').is(':visible')) {
                            $wrapper.find('#pl-list-due-datetime-set').show();
                        }
                    } else {
                        $wrapper.find('#pl-list-due-datetime-set, #pl-list-due-datetime-hours, #pl-list-due-datetime-minutes, #pl-list-due-datetime-clear').hide()
                    }
                }
            };

            $wrapper.find('#pl-list-due-datetime').datepicker(datepicker_options);
            icon_path = $('#pl-list-icon-dialog').find('[data-pl-icons-path]').data('pl-icons-path');
            list_color = getListColor(); // color before save
        };

        var init = function () {
            if ($wrapper.data('pl-ListDetails') || !list_id || list_id < 0) {
                return;
            }
            $wrapper.data('pl-ListDetails', true);

            $wrapper
                .on('submit', 'form', function (e) {
                    if (request_in_action) {
                        return;
                    }
                    request_in_action = true;

                    e.preventDefault();
                    var $this = $(this);
                    $this.find('#pl-list-details-save').removeClass('yellow').after($.pocketlists.$loading);
                    $.post('?module=list&action=save', $this.serialize(), function (r) {
                        $.pocketlists.$loading.remove();
                        if (r.status === 'ok') {
                            $.pocketlists_routing.redispatch();
                            // list_color = getListColor();
                            // updateList(r.data);
                            // $.pocketlists.updateAppCounter();
                            // $.pocketlists.reloadSidebar();
                            // hideListDetails();

                            // $.pocketlists.sendNotifications();
                        } else {
                            $wrapper.find('.error').show().delay(3000).hide();
                        }
                    }, 'json')
                        .always(function () {
                            request_in_action = false;
                        });
                    return false;
                }) // save
                .on('click', '.pl-list-details-cancel', function (e) {
                    e.preventDefault();
                    hideListDetails();
                })
                // .on('click', '[data-pl-action="list-delete"]', function (e) {
                //     e.preventDefault();
                //     deleteList();
                // })
                .on('click', '#pl-list-color a', function (e) {
                    e.preventDefault();
                    var color = $(this).data('pl-list-color');
                    setListColor(color); // just preview
                    $('#pl-list-color').find('input').val($(this).data('pl-list-color')).trigger('change');
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

                    $.waDialog({
                        header: $('#pl-list-icon-dialog').find('.dialog-header').html(),
                        content: $('#pl-list-icon-dialog').find('.dialog-content').html(),
                        footer: $('#pl-list-icon-dialog').find('.dialog-footer').html(),
                        onOpen: function ($dialog, dialog_instance) {

                            $dialog
                            .on('click', 'a[data-pl-list-icon]', function (e) {
                                e.preventDefault();
                                var icon = $(this).data('pl-list-icon'),
                                    $this = $(this);
                                $wrapper.find('#pl-list-icon-change').find('input').val($(this).data('pl-list-icon'));

                                $this.find('input').val(icon);
                                $wrapper.find('#pl-list-icon-change .icon i').css('background-image', 'url(' + icon_path + icon + ')');
                                $wrapper.find('#pl-list-details-save').addClass('yellow');
                                dialog_instance.close();
                                return false;
                            })
                            .on("click", ".cancel", function(e) {
                                e.preventDefault();
                                dialog_instance.close();
                            });
                        }
                    });
                })
                .on('change paste keyup', ':input', function () {
                    $wrapper.find('#pl-list-details-save').addClass('yellow');
                })
                .on('show.pl2', showListDetails)
                .on('hide.pl2', hideListDetails);

            $(window).scroll(function () {
                $.pocketlists.stickyDetailsSidebar();
            });
        };
        init();

        return {
            $el: $wrapper,
            trigger: function (event, data) {
                this.$el && this.$el.trigger(event, data);
            },
            isVisible: function () {
                return this.$el ? this.$el.is(':visible') : false;
            }
        };
    }($('#pl-list-details')));

    // add list
    var addNewList = function (id) {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        var name = $new_list_input.val().trim();
        name = name || $new_list_input.attr('placeholder');
        if (name.length) {
            var data = {
                name: name,
                type: 'checklist',
                pocket_id: pocket_id
            };
            if (data.name) {
                var $pl_done = $new_list_input.closest('.pl-list-title').find('.pl-done-label span').addClass('transparent').html($.pocketlists.$loading.css({
                    'background-size': '24px 24px',
                    width: 24,
                    height: 24,
                    opacity: 1,
                    margin: 0
                }));
                $.post(
                    '?module=list&action=update',
                    {
                        data: data,
                        id: id
                    },
                    function (r) {
                        $pl_done.removeClass('transparent');
                        $.pocketlists.$loading.removeAttr('style').remove();
                        if (r.status === 'ok') {
                            $.pocketlists.reloadSidebar();
                            // $.pocketlists.sendNotifications();
                            if (list_id === -1) {
                                $.wa.setHash('#/pocket/'+pocket_id+'/list/' + r.data.id + '/');

                            }
                        } else {

                        }
                    },
                    'json'
                ).always(function () {
                    request_in_action = false;
                });
            }
        }
    };
    // favorite list
    var favoriteList = function () {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

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
                request_in_action = false;
            },
            'json'
        );
    };
    var deleteList = function () {
        var $dialog_delete = $('#pl-dialog-delete-list-confirm');

        if ($dialog_delete.hasClass('dialog')) {
            $dialog_delete.show();
        } else {
            $.waDialog({
                content: $dialog_delete.find('.dialog-content').html(),
                footer: $dialog_delete.find('.dialog-buttons').html(),
                onOpen: function ($dialog, dialog_instance) {

                    $dialog
                    .on('click', '.cancel', function (e) {
                        e.preventDefault();
                        dialog_instance.close();
                    })
                    .on('click', '[type="submit"]', function(e) {
                        e.preventDefault();

                        if (request_in_action) {
                            return;
                        }
                        request_in_action = true;
    
                        $.post('?module=list&action=delete', {list_id: list_id}, function (r) {
                            if (r.status === 'ok') {
                                var $lists = $('[data-pl2-wrapper="lists"]');
                                if ($lists.length) {
                                    $lists.find('[data-pl-list-id="' + list_id + '"]').remove();
                                }
    
                                if (/#\/archive/.test(window.location.hash)) {
                                    $.wa.setHash('#/archive/');
                                } else if (/#\/pocket/.test(window.location.hash)) {
                                    $.wa.setHash(window.location.hash.replace('/list/'+list_id, ''));
                                } else {
                                    $.wa.setHash('#/todo/');
                                }
                                // $.pocketlists_routing.redispatch();
                            } else {
                            }
                            dialog_instance.close();
                            request_in_action = false;
                        }, 'json');
                        return false;
                    })
                    
                },
                
            });
        }
    };
    var archiveList = function ($dialog) {
        if (!$dialog && !confirm($_("Archiving the list will temporarily make this list and all it's items invisible. When you need this list in the future, simply unarchive it and continue from just where you stopped. Archive the list?"))) {
            return;
        }

        if (request_in_action) {
            return;
        }
        request_in_action = true;

        $dialog = $dialog || false;

        $.post('?module=list&action=archive', {list_id: list_id, archive: 1}, function (r) {
            if (r.status === 'ok') {
                $dialog && $dialog.close();
                if (/#\/pocket/.test(window.location.hash)) {
                    $.wa.setHash(window.location.hash.replace('/list/'+list_id, ''));
                } else {
                    $.wa.setHash('#/todo/');
                }
                $.pocketlists_routing.redispatch();
            } else {
            }
            request_in_action = false;
        }, 'json');
    };
    var completeAllItems = function ($dialog) {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        $dialog = $dialog || false;

        $.post('?module=list&action=complete', {list_id: list_id, status: 1}, function (r) {
            if (r.status === 'ok') {
                $dialog.close();
                //$.wa.setHash('#/list/' + list_id );
                $.pocketlists_routing.redispatch();
            } else {
            }
            request_in_action = false;
        }, 'json');
    };
    var unarchiveList = function () {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        $.post('?module=list&action=archive', {list_id: list_id, archive: 0}, function (r) {
            if (r.status === 'ok') {
                $.pocketlists.updateAppCounter();
                $.wa.setHash('#/list/' + list_id + '/');
                $.pocketlists.reloadSidebar();
            } else {
            }
            request_in_action = false;
        }, 'json');
    };
    var autoSort = function () {
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        $.post('?module=list&action=autoSort', {list_id: list_id}, function (r) {
            if (r.status === 'ok') {
                $.pocketlists_routing.redispatch();
            } else {

            }
            request_in_action = false;
        }, 'json');
    };
    var deselectList = function () {
        $list_wrapper.removeData('pl-clicked');
    };
    var init = function () {
        // will add new list if we can
        if ($new_list_input.length) {
            $new_list_input.focus();
            $new_list_input
                .on('keydown', function (e) {
                    $new_list_input.data('pl-can-add', true);
                    if (e.which === 13 && $new_list_input.data('pl-can-add')) {
                        e.preventDefault();
                        addNewList(list_id);
                        $.storage.set('pocketlists/lists/focus', 1);
                    }
                    if (e.which === 27) {
                        $new_list_input.data('pl-can-add', false).val('').removeClass('pl-unsaved');
                    }
                }).on('blur', function () {
                // $new_list_input.data('pl-can-add') && addNewList(list_id);
                $new_list_input.addClass('pl-unsaved');
            }).on('focus', function () {
                $new_list_input.removeClass('pl-unsaved');
            });
        }

        if (o.archive) {
            $list_wrapper.find(':checkbox').prop('disabled', true);
        }

        if ($.storage.get('pocketlists/lists/focus')) {
            setTimeout(function () {
                $('[data-pl2-item-textarea]').trigger('focus');
            }, 10);
            $.storage.del('pocketlists/lists/focus');
        }

        $uho.add($list_wrapper)
            .on('click', '[data-pl-action="list-edit"]', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $list_wrapper.data('pl-clicked', 2);

                $.pocketlists.scrollToTop(200, 80);

                ListDetails.$el.data('pl2-list-edit-action', $(this).data('pl2-list-edit-action'));
                ListDetails.trigger('show.pl2');
            })
            .on('click', '[data-pl-action="list-delete"]', function (e) {
                e.preventDefault();

                deleteList();
            })
            .on('click', '[data-pl-action="list-complete"]', function (e) {
                e.preventDefault();

                alert('TODO: execute .on(click, #pl-list-complete here ');
            })
            .on('click', '[data-pl-action="list-archive"]', function (e) {
                e.preventDefault();

                archiveList();
            })
            .on('click', '[data-pl-action="list-sort"]', function (e) {
                e.preventDefault();

                autoSort();
            })
            .on('click', '[data-pl-action="list-favorite"]', function (e) {
                e.preventDefault();

                favoriteList();
            })
            .on('click', '[data-pl-action="list-email"]', function (e) {
                e.preventDefault();
                e.stopPropagation();

                $.get('?module=list&action=emailDialog&id=' + list_id)
                .done(function (html) {

                    $.waDialog({
                        html: html,
                        onOpen: function ($dialog, dialog_instance) {

                            $dialog
                            .on("click", "[type=\"submit\"]", function(e) {
                                e.preventDefault();

                                var $this = $(this);

                                $this.after($.pocketlists.$loading);
                                $.post('?module=list&action=email', $this.serialize(), function (r) {
                                    $.pocketlists.$loading.remove();
                                    if (r.status === 'ok') {
                                        dialog_instance.close();
                                    } else {
                                        alert(r.errors);
                                    }
                                }, 'json');
                                
                            })
                            .on("click", ".cancel", function(e) {
                                e.preventDefault();
                                dialog_instance.close();
                            });
    
                        }
                    });

                })

            })
            .on('click', '#pl-list-complete', function (e) {
                e.stopPropagation();
                var $dialog_complete_all = $('#pl-dialog-list-archive-complete-all');

                if ($dialog_complete_all.hasClass('dialog')) {
                    $dialog_complete_all.show();
                } else {
                    $.waDialog({
                        content: $dialog_complete_all.find('.dialog-content').html(),
                        footer: $dialog_complete_all.find('.dialog-buttons').html(),
                        onOpen: function ($dialog, dialog_instance) {

                            $dialog.on('click', '[data-pl-action]', function (e) {
                                e.preventDefault();

                                var $button = $(this),
                                    action = $button.data('pl-action');

                                $button.after($.pocketlists.$loading);

                                if (action === 'list-complete-all') {
                                    completeAllItems(dialog_instance);
                                } else if (action === 'list-archive') {
                                    archiveList(dialog_instance);
                                } else if (action === 'cancel') {
                                    dialog_instance.close();
                                }
                            });
                        
                        }
                    });
                }
            })
            .on('click', '[data-pl-action="list-unarchive"]', function (e) {
                e.preventDefault();

                unarchiveList();
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
        list_id: list_id
    }
};
