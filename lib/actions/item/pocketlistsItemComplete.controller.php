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
            $item = $im->getByField(array(
                'list_id' => $list_id,
                'id' => $id
            ));
            if ($item['has_children']) {
                $tree = $im->getAllByList($list_id, $id);
                $this->changeComplete($item['id'], $tree[$item['id']], $status, $im);
            }
        }

    }

    /**
     * @param $item_id int
     * @param $item array
     * @param $status int
     * @param $im pocketlistsItemModel
     */
    private function changeComplete($item_id, $item, $status, $im)
    {
        $data = array(
            'status' => $status,
            'parent_id' => ($item['id'] == $item_id ? 0 : $item['parent_id']) // reset level for root item
        );
        if ($status) {
            $data['complete_datetime'] = date("Y-m-d H:i:s");
        }
        if (!$im->updateById($item['id'], $data)) {
            $this->errors[] = 'error while updating parent id: ' . $item['id'];
        };
        foreach ($item['childs'] as $i) {
            $this->changeComplete($item_id, $i, $status, $im);
        }
    }
}