<?php

class pocketlistsItemCompleteController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $status = waRequest::post('status', 0, waRequest::TYPE_INT);

        if ($list_id && $id) {
            $im = new pocketlistsItemModel();
            if (!$im->updateById(
                $id,
                array('status' => $status, 'complete_date' => date("Y-m-d H:i:s"), 'parent_id' => 0)
            )) {
                $this->errors[] = 'error while updating parent id: ' . $id;
            }
        }
    }
}