<?php

/**
 * Class pocketlistsListDetailsAction
 */
class pocketlistsListDetailsAction extends pocketlistsViewListAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $this->view->assign('list', $this->getList());
    }
}
