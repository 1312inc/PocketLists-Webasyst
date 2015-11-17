<?php

class pocketlistsItemSortController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $data = waRequest::post('data', array(), waRequest::TYPE_ARRAY);

        if ($list_id && $data) {
            $im = new pocketlistsItemModel();
            foreach ($data as $value) {
                if (!$im->updateById(
                    $value['id'],
                    array('parent_id' => $value['parent_id'], 'sort' => $value['sort'])
                )) {
                    $this->errors[] = 'error while updating parent id: ' . join(", ", $value);
                }
            }
        }
    }
}