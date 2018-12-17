<?php

/**
 * Class pocketlistsItemCommentsAction
 */
class pocketlistsItemCommentsAction extends waViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        if ($id) {
            $im = new pocketlistsItemModel();
            $item = $im->getById($id);
            $item = $im->extendItemData($item, false);
            $this->view->assign('item', $item);
            $this->view->assign(
                'pl2_attachments_path',
                wa()->getDataUrl('attachments/'.$item['id'].'/', true, pocketlistsHelper::APP_ID)
            );

            $this->view->assign('plurl', wa()->getAppUrl(pocketlistsHelper::APP_ID));
        }
    }

    private function markAsNewAndMatchLinks($comment)
    {
        $comment['comment'] = pocketlistsNaturalInput::matchLinks($comment['comment']);

        return $comment;
    }
}
