<?php

class pocketlistsItemMoveToListController extends waJsonController
{
    public function execute()
    {
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);


        if ($id > 0 && $list_id > 0) {
            $im = new pocketlistsItemModel();
            $lm = new pocketlistsListModel();

            $item = $im->getById($id);
            $list = $lm->getById($list_id);

            if ($item && $list && pocketlistsRBAC::canAccessToList($list_id)) {
                // todo: childs??
                if ($im->updateById(
                    $item['id'],
                    [
                        'sort'            => 0,
                        'list_id'         => $list['id'],
                        'update_datetime' => date('Y-m-d H:i:s'),
                    ]
                )
                ) {
                    $listItems = $im->getUndoneByList($list_id);
                    $curPos = 1;
                    /** @var pocketlistsItemModel $listItem */
                    foreach ($listItems as $listItem) {
                        if ($listItem->pk === $item['id']) {
                            continue;
                        }

                        $im->updateById($listItem->pk, ['sort' => $curPos++]);
                    }

                    $this->response = $id;
                } else {
                    $this->errors = 'db error';
                }
            } else {
                $this->errors = 'no such item or list or access error';
            }
        } else {
            $this->errors = 'no id';
        }
    }
}
