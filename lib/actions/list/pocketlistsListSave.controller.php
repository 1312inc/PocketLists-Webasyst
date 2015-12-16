<?php

class pocketlistsListSaveController extends waJsonController
{
    public function execute()
    {
        $list = waRequest::post('list', array(), waRequest::TYPE_ARRAY);

        if ($list) {
            $im = new pocketlistsListModel();
            if ($list['due_date'] && !empty($list['due_datetime_hours']) && !empty($list['due_datetime_minutes']))  {
                $list['due_datetime'] = date('Y-m-d H:i:00', strtotime($list['due_date']." ".$list['due_datetime_hours'].":".$list['due_datetime_minutes'].":00"));
                unset($list['due_datetime_hours']);
                unset($list['due_datetime_minutes']);
            } else {
                $list['due_datetime'] = null;
            }

            $list['due_date'] = $list['due_date'] ? date('Y-m-d', strtotime($list['due_date'])) : null;

            $list['update_datetime'] = date("Y-m-d H:i:s");
            $list['contact_id'] = wa()->getUser()->getId();
            if ($im->update($list['id'], $list)) {
                $this->response = 'ok';
            } else {
                $this->errors = 'error while saving item';
            }
        }
    }
}