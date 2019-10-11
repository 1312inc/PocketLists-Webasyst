(function ($) {
    'use strict';

    $.pocketlists_pro_routing = {
        options: {
            user_id: 0
        },
        init: function (options) {
            $.store && !$.storage && ($.storage = new $.store());

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
            // hash += '/pro/';
            //
            if (hash[0] != '#') {
                // if (hash[0] != '/') {
                //     hash = '/pro/' + hash;
                // }
                hash = '#' + hash;
            // } else if (hash[1] && hash[1] != '/') {
            //     hash = '#/pro/' + hash.substr(1);
            }
            //
            // if (hash == '#/pro/') {
            //     return '';
            // }

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
                if (hash[0] === 'pro') {
                    var actionName = "",
                        attrMarker = hash.length - 1,
                        lastValidActionName = null,
                        lastValidAttrMarker = null;

                    for (var i = 1; i < hash.length; i++) {
                        var h = hash[i];
                        if (i < 2) {
                            if (i === 1) {
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
                        console.info('dispatch', [actionName + 'Action', attr]);
                        this[actionName + 'Action'].apply(this, attr);

                        if (actionName !== 'debug') {
                            $.storage.set('/pocketlists/hash/' + this.options.user_id, hash.join('/'));
                        }

                        this.postExecute();
                    }
                }
            }
        },
        redispatch: function () {
            this.prevHash = null;
            this.dispatch();
        },
        postExecute: function () {
            $.pocketlists.scrollToContent()
        },
        activityAction: function () {
            this.load('?plugin=pro&module=activity&action=activity', this.setHtmlContent);
            $.pocketlists.highlightSidebar();
        },
        pocketAction: function (id) {
            //var self = this;
            var label_id = decodeURIComponent(this.getHash().substr(('#/pro/pocket/' + id + '/status/').length).replace('/', '')) || 0;

            var that = this,
                $content = $('#content'),
                loadLabelItems = function(){
                    that.load(
                        '?plugin=pro&module=label&action=pocket&pocket_id=' + id + '&label_id=' + label_id,
                        function (labelHtml) {
                            $content.find('#pl-list-content').html(labelHtml);
                        })
                };

            if (!$content.find('[data-pl2-pocket-wrapper="'+id+'"]').length) {
                that.load(
                    '?module=pocket&id=' + id,
                    function (html) {
                        var $html = $(html);

                        $html.find('#pl-list-content').empty();
                        $content.empty().append($html);
                        loadLabelItems()
                    }
                );
            } else {
                loadLabelItems()
            }
        },
        pocketActivityAction: function (id) {
            $.pocketlists.skipHighlightSidebar = true;
            var that = this,
                $content = $('#content'),
                loadActivity = function(){
                    that.load(
                        '?plugin=pro&module=activity&action=pocket&entity_id=' + id,
                        function (labelHtml) {
                            $content.find('#pl-list-content').html(labelHtml);

                            $.pocketlists.skipHighlightSidebar = false;
                            $.pocketlists.highlightSidebar(null, '#/pocket/' + id + '/');
                        })
                };

            if (!$content.find('[data-pl2-pocket-wrapper="'+id+'"]').length) {
                that.load(
                    '?module=pocket&pocket_id=' + id,
                    function (html) {
                        var $html = $(html);

                        $html.find('#pl-list-content').empty();
                        $content.empty().append($html);
                        loadActivity();
                    }
                );
            } else {
                loadActivity()
            }
        },
        boardAction: function (id) {
            id = id || 0;
            this.load('?plugin=pro&module=board&pocket_id=' + id, this.setHtmlContent);
            $.pocketlists.highlightSidebar();
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
            $.pocketlists.setTitle();
        }
    }
}(jQuery));
