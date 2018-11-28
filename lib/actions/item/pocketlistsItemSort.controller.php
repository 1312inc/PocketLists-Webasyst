<?php

/**
 * Class pocketlistsItemSortController
 */
class pocketlistsItemSortController extends waJsonController
{
    /**
     * @throws waDbException
     */
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $data = waRequest::post('data', [], waRequest::TYPE_ARRAY);
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);

        if ($list_id && $data) {
            $im = new pocketlistsItemModel();
            foreach ($data as $value) {
                if ($item_id && $item_id == $value['id']) { // update update_datetime only for root item
                    $value['update_datetime'] = date("Y-m-d H:i:s");
                }

                if (!$im->updateById($value['id'], $value)) {
                    $this->errors[] = 'error while updating parent id: '.join(", ", $value);
                }
            }
        }
    }
}
