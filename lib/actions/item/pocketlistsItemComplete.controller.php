<?php

class pocketlistsItemCompleteController extends waJsonController
{
    private $completed_items = array();
    private $list;

    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $status = waRequest::post('status', 0, waRequest::TYPE_INT);

        if ($list_id) {
            $im = new pocketlistsItemModel();
            $list = false;
            if ($id > 0) { // complete item/items
                $item = $im->getByField(
                    array(
                        'list_id' => $list_id,
                        'id' => $id
                    )
                );
                if ($item['has_children']) {
                    $tree = $im->getAllByList($list_id, $id);
                    $this->changeComplete($item['id'], $tree[$item['id']], $status, $im);
                } else {
                    $this->changeComplete($item['id'], $item + array('childs' => array()), $status, $im);
                }
            } elseif ($id === -1) { // complete list
                $tree = $im->getUndoneByList($list_id);
                $lm = new pocketlistsListModel();
                $list = $lm->getById($list_id);
                $this->changeComplete(0, array('id' => null, 'childs' => $tree), $status, $im);
            }
            pocketlistsNotifications::notifyAboutCompleteItems($this->completed_items, $list);
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
            $data['complete_contact_id'] = wa()->getUser()->getId();
        } else {
            $data['complete_contact_id'] = null;
        }
        if (!$im->updateById($item['id'], $data, null, true)) {
            $this->errors[] = 'error while updating parent id: ' . $item['id'];
        } else {
            $this->completed_items[] = array_merge($item, $data);
        };
        foreach ($item['childs'] as $i) {
            $this->changeComplete($item_id, $i, $status, $im);
        }
    }
}