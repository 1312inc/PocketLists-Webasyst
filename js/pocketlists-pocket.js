"use strict";

/**
 * object to work with list
 * - add
 * - print
 * - delete
 * - archive
 * - sort all
 */
$.pocketlists.Pocket = function ($pocket_wrapper, options) {
    var $sidebarPocketsWrapper = $('[data-pl2-sidebar-wrapper="pockets"]'),
        $loading = $('<i class="icon16 loading"></i>'),
        pocket_id = 0,
        request_in_action = false;

    var _settingDialog = function () {
        var pocketId = $(this).data('pl2-pocket-id') || 0;

        $('#pl-pocket-dialog').waDialog({
            'height': '250px',
            'width': '600px',
            'url': '?module=pocket&action=settingsDialog&id=' + pocketId,
            onLoad: function () {
                var d = this,
                    $dialogWrapper = $(d);

                $dialogWrapper
                    .on('click', '#pl-pocket-color a', function (e) {
                        e.preventDefault();

                        $dialogWrapper.find('#pl-pocket-color input').val($(this).data('pl-pocket-color'));
                        $(this).addClass('selected')
                            .siblings().removeClass('selected')
                    })
                    .on('click', '[data-pl2-action="delete-pocket"]', function (e) {
                        e.preventDefault();

                        _deletePocket.call(d, pocketId);
                    })
                ;
            },
            onSubmit: function (d) {
                d.find('.dialog-buttons input[type="button"]').after($loading);
                $.post('?module=pocket&action=save', d.find('form').serialize(), function (r) {
                    $loading.remove();
                    if (r.status === 'ok') {
                        $.pocketlists_routing.redispatch();
                        d.trigger('close');
                    } else {

                    }
                }, 'json');
                return false;
            }
        });
    };

    var _sidebarHandlers = function () {
        $sidebarPocketsWrapper.on('click', '[data-pl2-action="add-pocket"]', function (e) {
            e.preventDefault();

            _settingDialog();
        });
    }

    var _initSortList = function ($lists_wrapper) {
        $lists_wrapper.sortable({
            item: '[data-pl-list-id]',
            distance: 5,
            placeholder: 'pl-list-placeholder',
            tolerance: 'pointer',
            start: function (e, ui) {
                ui.placeholder.height(ui.helper.outerHeight());
            },
            stop: function (event, ui) {
                var getLists = function () {
                    var data = [];
                    $lists_wrapper.find('[data-pl-list-id]').each(function (i) {
                        var $this = $(this),
                            color = $this.attr('class').match(/pl-(.*)/);
                        data.push({
                            id: $this.data('pl-list-id'),
                            sort: i,
                            color: color[1]
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
    };

    function init() {
        pocket_id = $pocket_wrapper.data('pl2-pocket-wrapper');
        _addHandlers();
    }

    var _deletePocket = function (id) {
        id = id || pocket_id;
        var d = this;

        if (!confirm($_('DANGER! Pocket will be deleted with all its content including lists and to-dos. There will be no way to restore this data. Are you sure?'))) {
            return false;
        }

        if (request_in_action) {
            return;
        }
        request_in_action = true;

        $.post('?module=pocket&action=delete', {id: id}, function (r) {
            if (r.status === 'ok') {
                $(d).trigger('close');
                $.wa.setHash('#/todo/');
                $.pocketlists_routing.redispatch();
            }
        }, 'json').always(function () {
            request_in_action = false;
        });
    }

    function _addHandlers() {
        $pocket_wrapper.on('click', '[data-pl2-action="edit-pocket"]', function (e) {
            e.preventDefault();

            _settingDialog.call(this);
        });

        var $lists_wrapper = $('#pl-lists').find('.pl-lists');

        _initSortList($lists_wrapper);
    }

    if ($pocket_wrapper) {
        init();
    }

    return {
        showSettingsDoalog: _settingDialog
    }
};
