<?php

class pocketlistsCommentsDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $data = $this->get('id');

        if (empty($data)) {
            throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'id'), 400);
        } elseif (!is_array($data)) {
            throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'id'), 400);
        }

        $comments = [];
        $comment_ids = array_unique(array_filter($data));

        /** @var pocketlistsCommentFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsComment::class);
        if (!empty($comment_ids)) {
            $comments = $plf->findById($comment_ids);
            $comment_ids = [];

            /** @var pocketlistsComment $c */
            foreach ((array) $comments as $c) {
                $comment_ids[] = $c->getId();
            }
        }

        // validate
        foreach ($data as &$_comment) {
            /** set default */
            $_comment = [
                'id'      => ifempty($_comment),
                'success' => null,
                'errors'  => [],
            ];

            if (empty($_comment['id'])) {
                $_comment['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_comment['id'])) {
                $_comment['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            } elseif (!in_array($_comment['id'], $comment_ids)) {
                $_comment['success'] = true;
            }

            if (!empty($_comment['errors'])) {
                $_comment['success'] = false;
            }
        }

        $comments_ok = array_filter($data, function ($c) {
            return is_null($c['success']);
        });
        $comments_err = array_diff_key($data, $comments_ok);
        if (!empty($comments_ok)) {
            $logs = [];
            foreach ($comments as $comment) {
                try {
                    $id = $comment->getId();
                    if ($plf->delete($comment)) {
                        $success = true;
                        $logs[] = [
                            'id'         => $comment->getId(),
                            'item_id'    => $comment->getItemId(),
                            'list_id'    => $comment->getListId(),
                            'pocket_id'  => $comment->getPocketId(),
                            'contact_id' => $comment->getContactId(),
                            'comment'    => $comment->getComment(),
                        ];
                    } else {
                        $success = false;
                    }
                    foreach ($comments_ok as &$_comment_ok) {
                        if ($_comment_ok['id'] == $id) {
                            $_comment_ok['success'] = $success;
                            break;
                        }
                    }
                } catch (waException $we) {

                }
            }

            if ($logs) {
                pl2()->getModel(pocketlistsItem::class)->updateById(
                    array_filter(array_unique(array_column($logs, 'item_id'))),
                    ['activity_datetime' => date('Y-m-d H:i:s')]
                );

                $this->saveLog(
                    pocketlistsLog::ENTITY_COMMENT,
                    pocketlistsLog::ACTION_DELETE,
                    $logs
                );
            }
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($comments_ok, $comments_err),
            [
                'id'
            ], [
                'id' => 'int'
            ]
        );
    }
}
