<?php

class pocketlistsItemDataAction extends waViewAction
{
    public function execute()
    {
        if (waRequest::getMethod() == 'post') {
            $item = waRequest::post('item', array(), waRequest::TYPE_ARRAY);
            if ($item) {
                $im = new pocketlistsItemModel();
                $item['due_date'] = $item['due_date'] ? @waDateTime::format(
                    'Y-m-d',
                    strtotime($item['due_date'])
                ) : null;
                $item['update_datetime'] = date("Y-m-d H:i:s");
                $im->updateById($item['id'], $item);

                $this->view->assign('item', $im->getById($item['id']));
            }
        }

    }
}