<?php

/**
 * Class pocketlistsItemSortController
 */
class pocketlistsItemSortController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function execute()
    {
        $data = waRequest::post('data', [], waRequest::TYPE_ARRAY);
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);

        $list = $this->getList();
        if (!pocketlistsRBAC::canAccessToList($list)) {
            throw new pocketlistsForbiddenException();
        }

        if ($data) {
            $im = pl2()->getModel(pocketlistsItem::class);
            foreach ($data as $value) {
                if (!$im->updateById($value['id'], $value)) {
                    $this->errors[] = 'error while updating parent id: '.join(", ", $value);
                }
            }
        }
    }
}
