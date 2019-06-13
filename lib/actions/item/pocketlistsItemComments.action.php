<?php

/**
 * Class pocketlistsItemCommentsAction
 */
class pocketlistsItemCommentsAction extends pocketlistsViewItemAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $id = waRequest::request('id', false, waRequest::TYPE_INT);
        if ($id) {
            $item = $this->getItem();

            $this->view->assign(
                [
                    'item'                 => $item,
                    'pl2_attachments_path' =>
                        wa()->getDataUrl('attachments/'.$item->getId().'/', true, pocketlistsHelper::APP_ID),
                ]
            );
        }
    }
}
