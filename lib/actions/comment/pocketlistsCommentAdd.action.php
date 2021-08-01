<?php

/**
 * Class pocketlistsCommentAddAction
 */
class pocketlistsCommentAddAction extends pocketlistsViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $itemId = waRequest::post('item_id', 0, waRequest::TYPE_INT);
        $commentText = waRequest::post('comment', false, waRequest::TYPE_STRING_TRIM);

        if ($itemId && $commentText !== '') {
            /** @var pocketlistsCommentFactory $commentFactory */
            $commentFactory = pl2()->getEntityFactory(pocketlistsComment::class);

            /** @var pocketlistsItemFactory $itemFactory */
            $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
            /** @var pocketlistsItem $item */
            $item = $itemFactory->findById($itemId);

            if ($item) {
                if ($item->getListId()) {
                    $list = $item->getList();
                    if (!$list) {
                        $this->view->assign(
                            'error',
                            [
                                'code'    => 404,
                                'message' => _w('Not found'),
                            ]
                        );
                        $this->setTemplate(pl2()->getUI2TemplatePath('templates/include%s/error.html'));

                        return;
                    }

                    if (!pocketlistsRBAC::canAccessToList($list)) {
                        $this->view->assign(
                            'error',
                            [
                                'code'    => 403,
                                'message' => _w('Access denied'),
                            ]
                        );
                        $this->setTemplate(pl2()->getUI2TemplatePath('templates/include%s/error.html'));

                        return;
                    }
                }

                /** @var pocketlistsComment $comment */
                $comment = $commentFactory->createNew();
                $comment
                    ->setItem($item)
                    ->setContactId(wa()->getUser()->getId())
                    ->setComment($commentText)
                    ->setCreateDatetime(date('Y-m-d H:i:s'));

                if ($commentFactory->insert($comment)) {
                    $this->logAction(
                        pocketlistsLogAction::ITEM_COMMENT,
                        [
                            'list_id'    => $item->getListId(),
                            'comment_id' => $comment->getId(),
                            'item_id'    => $item->getId(),
                        ]
                    );

                    (new pocketlistsNotificationAboutNewComment())->notify($comment);

                    $this->logService->add(
                        $this->logService->getFactory()->createNewCommentLog(
                            (new pocketlistsLogContext())->setComment($comment)
                        )
                    );

                    $this->view->assign('comment', $comment);
                } else {
//                    $this->errors = 'error while adding new item comment';
                }
            }
        }

        $this->setTemplate(pl2()->getUI2TemplatePath('templates/actions%s/comment/Comment.html'));
    }
}
