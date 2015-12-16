<?php

class pocketlistsItemDataAction extends waViewAction
{
    public function execute()
    {
        if (waRequest::getMethod() == 'post') {
            $item = waRequest::post('item', array(), waRequest::TYPE_ARRAY);
            if ($item) {
                $im = new pocketlistsItemModel();

                pocketlistsHelper::getDueDatetime($item);
                $item['update_datetime'] = date("Y-m-d H:i:s");
                $im->updateWithCalcPriority($item['id'], $item);
                $this->view->assign('item', $im->getById($item['id']));
            }
        }

    }
}