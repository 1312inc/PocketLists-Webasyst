<?php

class pocketlistsItemMoveController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $data = waRequest::post('data', array(), waRequest::TYPE_ARRAY);

        if ($list_id && $data) {
            $im = new pocketlistsItemModel();
            foreach ($data as $value) {
                if (isset($value['parent_id'])) { // update parent
                    if (!$im->updateById($value['id'], array('parent_id' => $value['parent_id']))) {
                        $this->errors[] = 'error while updating parent id: ' . join(", ", $value);
                    }
                }
                if (isset($value['before_id'])) { // update sorting
                    // resort
                    $im->move($list_id, $value['id'], $value['before_id']);
                }
            }
        }
    }
}