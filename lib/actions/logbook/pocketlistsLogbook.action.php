<?php

/**
 * Class pocketlistsLogbookAction
 */
class pocketlistsLogbookAction extends pocketlistsViewAction
{
    const DEFAULT_OFFSET = 30;

    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $offset = waRequest::get('offset', 0);

        $items = pl2()->getEntityFactory(pocketlistsItem::class)
            ->setOffset($offset * self::DEFAULT_OFFSET)
            ->setLimit(self::DEFAULT_OFFSET)
            ->findLogbook(null, false, true);

        $this->view->assign(
            [
                'items'                => $items,
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'user'                 => $this->user,
            ]
        );
    }
}
