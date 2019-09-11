<?php

/**
 * Class pocketlistsItemAddAction
 */
class pocketlistsItemAddAction extends pocketlistsViewItemAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     */
    public function runAction($params = null)
    {
        if (isset($this->params['teammate']) && $this->params['teammate'] instanceof pocketlistsContact) {
            $this->view->assign('assign_contact_photo', $this->params['teammate']->getUserpic());
        }

        $event = new pocketlistsEvent(
            pocketlistsEventStorage::WA_BACKEND_ITEM_ADD,
            null,
            ['external' => !empty($this->params['external'])]
        );
        $this->view->assign('backend_item_add', pl2()->waDispatchEvent($event));
    }
}
