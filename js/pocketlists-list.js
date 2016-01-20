(function ($) {
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
            list_id = parseInt($list_wrapper.find('#pl-list-id').val()),
            pocket_id = parseInt($('#pl-pocket-id').val()),
            $dialog_delete = $('#pl-dialog-delete-list-confirm'),
            $dialog_complete_all = $('#pl-dialog-list-archive-complete-all'),
            o = $.extend({}, {
                archive: false
            }, options);

        /**
         * for show and manipulate with list details
         * - show/hide container (p)
         * - change icon
         * - save details
         */
        var ListDetails = (function ($wrapper) {
            var icon_path = null;

            // show list details container and load html with list details from server
            var showListDetails = function () {
                $.pocketlists.scrollToTop(200, 80);
                $wrapper.html($.pocketlists.$loading).show().animate({
                    'right': '0%'
                }, 200);

                $.pocketlists.stickyDetailsSidebar();

                $.post('?module=list&action=details', {id: list_id}, function (html) {
                    $wrapper.html(html);
                    afterLoad();
                });
            };
            var hideListDetails = function () {
                $wrapper.animate({
                    'right': '-100%'
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
                        $this.find('#pl-list-details-save').removeClass('yellow').after($.pocketlists.$loading);
                        $.post('?module=list&action=save', $this.serialize(), function (r) {
                            $.pocketlists.$loading.remove();
                            if (r.status === 'ok') {
                                updateList(r.data);
                                $.pocketlists.updateAppCounter();
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
                    .on('click', '[data-pl-action="list-delete"]', function (e) {
                        e.preventDefault();
                        deleteList();
                    })
                    .on('click', '#pl-list-color a', function (e) {
                        e.preventDefault();
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
                                    $wrapper.find('#pl-list-details-save').addClass('yellow');
                                    d.trigger('close');
                                    return false;
                                })
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
            var name = $new_list_input.val().trim();
            if (name.length) {
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
            }
        };
        // favorite list
        var favoriteList = function () {
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
        var deleteList = function () {
            $dialog_delete.waDialog({
                'height': '200px',
                'min-height': '200px',
                'width': '400px',
                onLoad: function () {
                },
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
        var archiveItem = function () {
            $.post('?module=list&action=archive', {list_id: list_id, archive: 1}, function (r) {
                if (r.status === 'ok') {
                    // todo: change pocket
                    $.wa.setHash('#/pocket/1/');
                } else {

                }
            }, 'json');
        };
        var autoSort = function () {
            $.post('?module=list&action=sort', {list_id: list_id}, function (r) {
                if (r.status === 'ok') {
                    $.pocketlists_routing.redispatch();
                } else {

                }
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
                        }
                        if (e.which === 27) {
                            $new_list_input.data('pl-can-add', false).val('');
                        }
                    }).on('blur', function () {
                        $new_list_input.data('pl-can-add') && addNewList(list_id);
                    });
            }

            if (o.archive) {
                $list_wrapper.find(':checkbox').prop('disabled', true);
            }

            $list_wrapper
                .on('click', function (e) {
                    if (!o.archive) {
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
                    }
                }) // open details
                .on('click', '[data-pl-action="list-edit"]', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    $list_wrapper.data('pl-clicked', 2);

                    $.pocketlists.scrollToTop(200, 80);

                    ListDetails.trigger('show.pl2');
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
                .on('click', '[data-pl-action="list-unarchive"]', function (e) {
                    e.preventDefault();

                    $.post('?module=list&action=archive', {list_id: list_id, archive: 0}, function (r) {
                        if (r.status === 'ok') {
                            $.pocketlists.updateAppCounter();
                            $.wa.setHash('#/pocket/' + pocket_id + '/list/' + list_id + '/');
                            $.pocketlists.highlightSidebar();
                        } else {
                        }
                    }, 'json');
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
}());