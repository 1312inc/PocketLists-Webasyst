(function ($) {
    'use strict';

    var waLoading = $.waLoading();

    $.ajaxSetup({
        beforeSend: function (xhr, settings) {
            if (settings.url.includes('?module=') && !settings.url.includes('?module=backend')) {
                waLoading.animate(6000, 99, true);
                this.complete = function () {
                    waLoading.done();
                };
            }
        },
    });

    $.storage = new $.store();
    $.pocketlists_routing = {
        options: {
            user_id: 0,
            $content: $('#content'),
            debug: false
        },
        init: function (options) {
            var that = this;
            that.options = options;
            if (typeof($.History) != "undefined") {
                $.History.bind(function () {
                    that.dispatch();
                });
            }

            var hash = window.location.hash;
            if (hash === '#/' || !hash) {
                hash = $.storage.get('/pocketlists/hash/' + that.options.user_id);
                if (hash && hash !== null && hash !== undefined) {
                    $.wa.setHash('#/' + hash);
                } else {
                    this.dispatch();
                }
            } else {
                $.wa.setHash(hash);
            }
        },
        // dispatch() ignores the call if prevHash == hash
        prevHash: null,
        skipScrollToTop: false,
        hash: null,
        /** Current hash. No URI decoding is performed. */
        getHash: function () {
            return this.cleanHash();
        },
        /** Make sure hash has a # in the begining and exactly one / at the end.
         * For empty hashes (including #, #/, #// etc.) return an empty string.
         * Otherwise, return the cleaned hash.
         * When hash is not specified, current hash is used. No URI decoding is performed. */
        cleanHash: function (hash) {
            if (typeof hash == 'undefined') {
                // cross-browser way to get current hash as is, with no URI decoding
                hash = window.location.toString().split('#')[1] || '';
            }

            if (!hash) {
                return '';
            } else if (!hash.length) {
                hash = '' + hash;
            }
            while (hash.length > 0 && hash[hash.length - 1] === '/') {
                hash = hash.substr(0, hash.length - 1);
            }
            hash += '/';

            if (hash[0] != '#') {
                if (hash[0] != '/') {
                    hash = '/' + hash;
                }
                hash = '#' + hash;
            } else if (hash[1] && hash[1] != '/') {
                hash = '#/' + hash.substr(1);
            }

            if (hash == '#/') {
                return '';
            }

            return hash;
        },
        // if this is > 0 then this.dispatch() decrements it and ignores a call
        skipDispatch: 0,
        /** Cancel the next n automatic dispatches when window.location.hash changes */
        stopDispatch: function (n) {
            this.skipDispatch = n;
        },
        /** Implements #hash-based navigation. Called every time location.hash changes. */
        dispatch: function (hash) {
            if (this.skipDispatch > 0) {
                this.skipDispatch--;
                return false;
            }
            if (hash === undefined || hash === null) {
                hash = window.location.hash;
            }
            hash = hash.replace(/(^[^#]*#\/*|\/$)/g, '');
            /* fix syntax highlight*/
            if (this.hash !== null) {
                this.prevHash = this.hash;
            }
            this.hash = hash;
            if (hash) {
                hash = hash.split('/');
                if (hash[0]) {
                    var actionName = "";
                    var attrMarker = hash.length;
                    var lastValidActionName = null;
                    var lastValidAttrMarker = null;
                    for (var i = 0; i < hash.length; i++) {
                        var h = hash[i];
                        if (i < 2) {
                            if (i === 0) {
                                actionName = h;
                            } else if (parseInt(h, 10) != h && h.indexOf('=') == -1) {
                                actionName += h.substr(0, 1).toUpperCase() + h.substr(1);

                            } else {
                                break;
                            }
                            if (typeof(this[actionName + 'Action']) == 'function') {
                                lastValidActionName = actionName;
                                lastValidAttrMarker = i + 1;
                            }
                        } else {
                            break;
                        }
                    }
                    attrMarker = i;

                    if (typeof(this[actionName + 'Action']) !== 'function' && lastValidActionName) {
                        actionName = lastValidActionName;
                        attrMarker = lastValidAttrMarker;
                    }

                    var attr = hash.slice(attrMarker);
                    if (typeof(this[actionName + 'Action']) == 'function') {
                        this.preExecute(actionName);
                        this.options.debug && console.info('dispatch', [actionName + 'Action', attr]);
                        this[actionName + 'Action'].apply(this, attr);

                        if (actionName !== 'debug') {
                            $.storage.set('/pocketlists/hash/' + this.options.user_id, hash.join('/'));
                        }
                        this.postExecute(actionName);
                    } else {
                        this.options.debug && console.info('Invalid action name:', actionName + 'Action');
                    }
                } else {
                    this.preExecute();
                    this.defaultAction();
                    this.postExecute();
                }
            } else {
                this.preExecute();
                this.defaultAction();
                this.postExecute();
            }
        },
        redispatch: function () {
            this.prevHash = null;
            this.dispatch();
        },
        preExecute: function () {
            $(window).off('scroll.pl2');
            $.pocketlists.highlightSidebar();
        },
        postExecute: function () {
            $.pocketlists.reloadSidebar();
            this.heartbeat();
            if (!this.skipScrollToTop) {
                $.pocketlists.scrollToContent();
            } else {
                this.skipScrollToTop = false;
            }
        },

        heartbeat: function () {
            $.post('?module=backendJson&action=heartbeat');
        },

        // actions
        defaultAction: function () {
            this.todoAction();
            // this.pocketAction();
        },
        pocketAction: function (id) {
            //var self = this;
            var list_id = decodeURIComponent(this.getHash().substr(('#/pocket/' + id + '/list/').length).replace('/', '')) || 0;
            //var load_list = this.getHash().indexOf('list') > 0;
            if (list_id) {
                if (list_id === 'new') {
                    list_id = -1;
                }
            }
            id = id || 0;
            var $list_name = $('#pl-list-name'),
                $content = $('#content');

            if ($list_name.length) {
                $list_name.after('<i class="icon16 loading">');
            }

            // if ($content.find('[data-pl2-pocket-wrapper]').data('pl2-pocket-wrapper') == id) {
            //     this.load('?module=list&pocket_id=' + id + '&id=' + list_id, function (html) {
            //         var $normalWrapper = $content.find('#pl-list-content');
            //         if ($normalWrapper.length) {
            //             $normalWrapper.replaceWith(html);
            //         } else {
            //             $content.find('#pl-list-icon-dialog').after(html);
            //         }
            //         // -_-
            //         $content.find('[data-pl2-wrapper="lists"] [data-pl-list-id] a').removeClass('pl-is-selected');
            //         $content.find('[data-pl2-wrapper="lists"] [data-pl-list-id="'+list_id+'"] a').addClass('pl-is-selected');
            //     });
            // } else {
                this.load('?module=pocket&id=' + id + '&list_id=' + list_id, function (html) {
                    $content.html(html);
                });
            // }

            // todo: load list separately
            // this.load('?module=pocket&id=' + id + '&list_id=' + list_id, this.setHtmlContent);
        },
        listsAction: function () {
            this.pocketAction();
        },
        listAction: function (id) {
            this.todoAction();
        },
        archiveAction: function (id) {
            id = id || 0;
            this.load('?module=archive&id=' + id, this.setHtmlContent);
        },
        searchAction: function (term) {
            term = term || '';
            this.load('?module=search&term=' + term, this.setHtmlContent);
        },
        logbookAction: function () {
            this.load('?module=logbook', this.setHtmlContent);
        },
        settingsAction: function (a,b,c,d) {
            var that = this,
                loadPlugin = function (plugin, settings) {
                    return that.load('?plugin='+ plugin + '&module=settings&action=' + settings, function (html) {
                        $('[data-pl2-settings-content]').html(html);
                        def.resolve();
                    });
                },
                $wrapper = $('[data-pl2-settings-content]'),
                def = $.Deferred();

            if (!$wrapper.length) {
                this.load('?module=settings', function (html) {
                    var $html = $(html);

                    if (a === 'plugin') {
                        $html.find('#pl-settings-form').html('<i class="icon16 loading">');
                        loadPlugin(b, c);
                    }

                    $('#content').empty().append($html);
                });
            } else if (a === 'plugin') {
                loadPlugin(b, c);
            } else {
                this.load('?module=settings', this.setHtmlContent);
            }

            def.done(function () {
                $('[data-pl2-settings-sidebar]').find('li').removeClass('selected').end()
                    .find('[href*="'+that.hash+'"]').closest('li').addClass('selected');
            })
        },
        aboutAction: function () {
            this.load('?module=about', this.setHtmlContent);
        },
        debugAction: function () {
            this.load('?module=debug', this.setHtmlContent);
        },
        upgradeAction: function () {
            this.load('?module=upgrade', this.setHtmlContent);
        },
        todoAction: function () {
            this.load('?module=todo', this.setHtmlContent);
        },
        activityAction: function () {
            this.load('?module=activity', this.setHtmlContent);
        },
        commentsAction: function () {
            this.load('?module=comments', this.setHtmlContent);
        },
        favoritesAction: function () {
            this.load('?module=favorites', this.setHtmlContent);
        },
        teamAction: function (teammate) {
            teammate = teammate || 0;
            this.load('?module=team&teammate=' + teammate, this.setHtmlContent);
        },
        appAction: function (app) {
            app = app || 0;
            this.load('?module=app&app=' + app, this.setHtmlContent);
        },
        pluginsAction: function (params) {
            if ($('#wa-plugins-container').length) {
                $.plugins.dispatch(params);
            } else {
                this.load('?module=plugins', this.setHtmlContent);
            }
        },
        /** Helper to load data into main content area. */
        load: function (url, options, fn) {
            if (typeof options === 'function') {
                fn = options;
                options = {};
            } else {
                options = options || {};
            }
            var r = Math.random();
            this.random = r;
            var self = this;
            return $.get(url, function (result, textStatus, jqXHR) {
                if ((typeof options.check === 'undefined' || options.check) && self.random != r) {
                    // too late: user clicked something else.
                    return;
                }
                if (typeof fn === 'function') {
                    fn.call(this, result);
                }
            });
        },
        setHtmlContent: function (html) {
            $('#content').html(html);
        }
    }
}(jQuery));
