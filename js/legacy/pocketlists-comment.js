"use strict";

$.pocketlists.Comments = function ($list_items_wrapper, options) {
    var request_in_action = false,
        $w = $('[data-pl="comments"]'),
        load_next_page = null,
        $loading = $w.find('.lazyloading'),
        o = $.extend({}, {
            lazy: false,
            appUrl: '',
            wa_url: '',
            standAloneItemAdd: false
        }, options);

    var countComments = function ($counter) {
        return $counter.closest('[data-id]').find('[data-pl-comment-id]').length;
    };

    var updateCommentsCount = function (id) {
        var $counter = $list_items_wrapper.find('.pl-item-wrapper[data-id="' + id + '"] .pl-comment-count');

        if ($counter.length) {
            $counter
                .addClass('pl-comment-count-show')
                .html('<i class="icon16 pl comments"></i>' + countComments($counter));
        }
    };

    var addComment = function () {
        var $this = $(this),
            $wrapper = $this.closest('[data-pl2-item-id]'),
            $comments_wrapper = $wrapper.find('.pl-chat-contents'),
            $comment_wrapper = $this.closest('[data-pl-comment-id]'),
            item_id = parseInt($wrapper.data('pl2-item-id')) || parseInt($comment_wrapper.data('pl-item-id')),
            $reply_wrapper = $wrapper.find('.pl-reply'),
            $userpic = $reply_wrapper.find('.icon16');

        if (!item_id) {
            return;
        }
        if (request_in_action) {
            return;
        }
        request_in_action = true;

        $userpic.hide();
        $reply_wrapper.prepend($.pocketlists.$loading.css({
            'margin-top': 1,
            'margin-left': 12
        }));
        $.post(
            o.appUrl + '?module=comment&action=add',
            {
                item_id: item_id,
                comment: $this.val().trim()
            },
            function (html) {
                $.pocketlists.$loading.removeAttr('style').remove();
                $userpic.show();

                $comment_wrapper.length ? $comment_wrapper.after(html) : $comments_wrapper.append(html);
                $this.val('');
                $.pocketlists.resizeTextarea($this);
                if ($comment_wrapper.length) {
                    hideComment.call($this.get(0));
                }

                updateCommentsCount(item_id);

                request_in_action = false;
            }
        );
    };

    var deleteComment = function () {
        if (!confirm($_('You are about to permanently delete this comment. Delete?'))) {
            return;
        }

        if (request_in_action) {
            return;
        }
        request_in_action = true;

        var $this = $(this),
            $comment_wrapper = $this.closest('[data-pl-comment-id]'),
            comment_id = $comment_wrapper.data('pl-comment-id'),
            item_id = $comment_wrapper.data('pl-item-id');

        $.post(
            o.appUrl + '?module=comment&action=delete',
            {
                id: comment_id
            },
            function (r) {
                $.pocketlists.$loading.removeAttr('style').remove();
                if (r.status === 'ok') {
                    $comment_wrapper.slideUp(200, function () {
                        $comment_wrapper.remove();

                        updateCommentsCount(item_id);
                    });
                }
                request_in_action = false;
            },
            'json'
        );
    };

    var hideComment = function () {
        $(this).closest('.pl-reply').hide();
    };

    function _init() {
        $list_items_wrapper
            .on('click', '[data-pl-action="comment-reply"]', function (e) {
                e.preventDefault();
                var $comment_wrapper = $(this).closest('[data-pl-comment-id]'),
                    $reply_wrapper = $comment_wrapper.find('.pl-reply');

                $reply_wrapper.show();
                setTimeout(function () {
                    $reply_wrapper.find('textarea').trigger('focus');
                }, 400);

            })
            .on('keydown', '.pl-chat .pl-reply textarea', function (e) {
                if (!e.shiftKey && e.which === 13) {
                    e.preventDefault();

                    addComment.call(this);
                } else if (e.which === 27) {
                    // hideComment.call(this);
                }
            })
            .on('change cut keydown drop paste', '.pl-chat .pl-reply textarea', function () {
                var $textarea = $(this);
                window.setTimeout(function () {
                    $.pocketlists.resizeTextarea($textarea)
                }, 0);
            })
            .on('click', '[data-pl-action="comment-delete"]', function (e) {
                e.preventDefault();

                deleteComment.call(this);
            })
            .on('blur', '.pl-chat .pl-reply textarea', function (e) {
                var $this = $(this),
                    comment = $this.val().trim();
                if (comment) {
                    $this.addClass('pl-unsaved');
                    if (!o.standAloneItemAdd) {
                        $.pocketlists.enable_prevent_close_browser();
                    }
                }
            })
            .on('focus', '.pl-chat .pl-reply textarea', function (e) {
                var $this = $(this);
                $this.removeClass('pl-unsaved');
                if (!o.standAloneItemAdd) {
                    $.pocketlists.disable_prevent_close_browser();
                }
            });

        if (o.lazy) {
            var is_bottom = false,
                prev_scroll_pos = 0,
                offset = 1,
                this_is_the_end = false;

            $(window).on('scroll', function () {
                if (this_is_the_end) {
                    return;
                }

                var scroll_pos = $(document).scrollTop() + $(window).outerHeight(),
                    doc_h = $(document).outerHeight() - 20;

                if (prev_scroll_pos < scroll_pos) {
                    if (!is_bottom && scroll_pos >= doc_h) {
                        is_bottom = true;
                        if (request_in_action) {
                            return;
                        }
                        $loading.show();
                        request_in_action = true;

                        $.get('?module=comments&offset=' + offset, function (html) {
                            $loading.hide();
                            var html = $(html).find('[data-pl-comments="latest-discussions"]').html();
                            if ($.trim(html).length) {
                                offset++;
                            } else {
                                this_is_the_end = true;
                            }
                            $w.find('[data-pl-comments="latest-discussions"]').append(html);
                            request_in_action = false;
                        });
                    } else {
                        is_bottom = false;
                    }
                }
                prev_scroll_pos = scroll_pos;
            });
        }
    }

    _init();
}