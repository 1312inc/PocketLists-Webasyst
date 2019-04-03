<?php

/**
 * Class pocketlistsItemCommentsAction
 */
class pocketlistsItemCommentsAction extends pocketlistsViewItemAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $id = waRequest::request('id', false, waRequest::TYPE_INT);
        if ($id) {
            $item = $this->getItem();

            $this->view->assign(
                [
                    'current_user'         => pl2()->getUser(),
                    'item'                 => $item,
                    'pl2_attachments_path' =>
                        wa()->getDataUrl('attachments/'.$item->getId().'/', true, pocketlistsHelper::APP_ID),
                    'plurl'                => wa()->getAppUrl(pocketlistsHelper::APP_ID),
                ]
            );
        }
    }
}
