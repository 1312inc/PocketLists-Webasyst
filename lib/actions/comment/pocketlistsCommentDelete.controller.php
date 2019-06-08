<?php

/**
 * Class pocketlistsCommentDeleteController
 */
class pocketlistsCommentDeleteController extends pocketlistsJsonController
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $comment_id = waRequest::post('id', 0, waRequest::TYPE_INT);

        if ($comment_id) {
            /** @var pocketlistsCommentFactory $commentFactory */
            $commentFactory = pl2()->getEntityFactory(pocketlistsComment::class);
            $comment = $commentFactory->findById($comment_id);
            if ($comment instanceof pocketlistsComment) {
                $item = $comment->getItem();

                if ($item->getListId()) {
                    $list = $item->getList();
                    if (!$list) {
                        throw new waException(_w('Not found'), 404);

                    }

                    if (!pocketlistsRBAC::canAccessToList($list)) {
                        throw new waException(_w('Access denied'), 403);
                    }
                }

                if ((time() - strtotime($comment->getCreateDatetime()) < 60 * 60 * 24)
                    && $comment->getContact()->isMe()
                    && $commentFactory->delete($comment)
                ) {
                    $this->logService->add(
                        $this->logService->getFactory()->createNewCommentLog(
                            (new pocketlistsLogContext())->setComment($comment),
                            pocketlistsLog::ACTION_DELETE
                        )
                    );

                    $this->response = 'ok';
                } else {
                    $this->errors = 'error while deleting item comment';
                }
            } else {
                $this->errors = 'no comment with such id';
            }
        } else {
            $this->errors = 'no id';
        }
    }
}
