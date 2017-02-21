<?php

class pocketlistsComplete extends waJsonController
{
    protected $completed_items = array();

    /**
     * @param $item_id int
     * @param $item array
     * @param $status int
     * @param $im pocketlistsItemModel
     */
    protected function changeComplete($item_id, $item, $status, $im)
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
        if ($item['id']) {
            if (!$im->updateById($item['id'], $data, null, true)) {
                $this->errors[] = 'error while updating parent id: ' . $item['id'];
            } else {
                $item = array_merge($item, $data);
                $this->completed_items[] = $im->prepareOutput($item);
            };
        }
        foreach ($item['childs'] as $i) {
            $this->changeComplete($item_id, $i, $status, $im);
        }
    }
}
