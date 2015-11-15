<?php

class pocketlistsItemUpdateParentController extends waJsonController
{
    public function execute()
    {
        $data = waRequest::post('data', array(), waRequest::TYPE_ARRAY);

        if ($data) {
            $im = new pocketlistsItemModel();
            foreach ($data as $value) {
                if (!$im->updateById($value['id'], array('parent_id' => $value['parent_id']))) {
                    $this->errors[] = 'error while updating parent id: ' . join(", ", $value);
                }
            }
        }
    }
}