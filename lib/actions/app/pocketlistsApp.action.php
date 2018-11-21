<?php

/**
 * Class pocketlistsAppAction
 */
class pocketlistsAppAction extends waViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $app_id = waRequest::get('id');
        if ($app_id) {
            $im = new pocketlistsItemModel();
            $items = $im->getAppItems($app_id);
            $this->view->assign(
                [
                    'items_undone'     => $im->getProperSort($im->extendItemData($items[0])),
                    'items_done'       => $im->extendItemData($items[1]),
                    'count_done_items' => count($items[1]),
                ]
            );
        }

        $this->view->assign(
            [
                'attachments_path' => wa()->getDataUrl('attachments/', true),
                'print'            => waRequest::get('print', false),
                'app'              => wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp($app_id),
            ]
        );
    }
}
