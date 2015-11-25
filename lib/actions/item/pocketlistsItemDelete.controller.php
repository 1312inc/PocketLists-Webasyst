<?php

class pocketlistsItemDeleteController extends waJsonController
{
    private $delete_ids = array();

    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);

        if ($list_id && $id) {
            $im = new pocketlistsItemModel();
            $item = $im->getByField(
                array(
                    'list_id' => $list_id,
                    'id' => $id
                )
            );
            if ($item['has_children']) {
                $tree = $im->getAllByList($list_id, $id);
                $this->getDeleteArray($item['id'], $tree[$item['id']]);
            } else {
                $this->getDeleteArray($item['id'], array('id' => $item['id'], 'childs' => array()));
            }
            $lm = new pocketlistsItemModel();
            $lm->deleteById(array_values($this->delete_ids));

            $this->response = array('id' => $item['id']);
        }

    }

    /**
     * @param $item_id int
     * @param $item array
     */
    private function getDeleteArray($item_id, $item)
    {
        $this->delete_ids[] = $item['id'];
        foreach ($item['childs'] as $i) {
            $this->getDeleteArray($item_id, $i);
        }
    }
}