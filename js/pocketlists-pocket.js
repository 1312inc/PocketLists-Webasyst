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
        $loading = $('<i class="icon16 loading"></i>');

    var settingDialog = function () {
        var pocketId = $(this).data('pl2-pocket-id') || 0;

        $('#pl-pocket-dialog').waDialog({
            'height': '250px',
            'width': '600px',
            'url': '?module=pocket&action=settingsDialog&id=' + pocketId,
            onLoad: function () {
                var $dialogWrapper = $(this);

                $dialogWrapper.on('click', '#pl-pocket-color a', function (e) {
                    e.preventDefault();

                    $dialogWrapper.find('#pl-pocket-color input').val($(this).data('pl-pocket-color'));
                    $(this).addClass('selected')
                        .siblings().removeClass('selected')
                });
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

    var initSortList = function ($lists_wrapper) {
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
        addHandlers();
    }

    function addHandlers() {

        $sidebarPocketsWrapper.on('click', '[data-pl2-action="add-pocket"]', function (e) {
            e.preventDefault();

            settingDialog.call(this);
        });

        $pocket_wrapper.on('click', '[data-pl2-action="edit-pocket"]', function (e) {
            e.preventDefault();

            settingDialog.call(this);
        });

        var $lists_wrapper = $('#pl-lists').find('.pl-lists');

        initSortList($lists_wrapper);
    }

    init();
};