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
            ->findLogbook(null, false, true, $offset * self::DEFAULT_OFFSET, self::DEFAULT_OFFSET);

        $this->view->assign(
            [
                'items'                => $items,
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'user'                 => $this->user,
            ]
        );
    }
}
