<?php

class pocketlistsItemDataAction extends waViewAction
{
    public function execute()
    {
        if (waRequest::getMethod() == 'post') {
            $item = waRequest::post('item', array(), waRequest::TYPE_ARRAY);
            if ($item) {
                $im = new pocketlistsItemModel();

                if ($item['due_date'] && !empty($item['due_datetime_hours']) && !empty($item['due_datetime_minutes']))  {
                    $item['due_datetime'] = date('Y-m-d H:i:00', strtotime($item['due_date']." ".$item['due_datetime_hours'].":".$item['due_datetime_minutes'].":00"));
                    unset($item['due_datetime_hours']);
                    unset($item['due_datetime_minutes']);
                } else {
                    $item['due_datetime'] = null;
                }

                $item['due_date'] = $item['due_date'] ? date('Y-m-d', strtotime($item['due_date'])) : null;

                $item['update_datetime'] = date("Y-m-d H:i:s");

                $im->updateWithCalcPriority($item['id'], $item);

                $this->view->assign('item', $im->getById($item['id']));
            }
        }

    }
}