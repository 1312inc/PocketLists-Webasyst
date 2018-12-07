<?php

class pocketlistsTestCreateItemsCli extends waCliController
{
    public function run($params = null)
    {
        $list = waRequest::param('list', false, waRequest::TYPE_INT);
        $limit = waRequest::param('limit', 1000, waRequest::TYPE_INT);

        if (!$list) {
            return;
        }

        $list = pocketlistsListModel::model()->findByPk($list);
        if (!$list) {
            return;
        }

        while ($limit--) {
            $item = new pocketlistsItemModel(
                [
                    'list_id'         => $list->pk,
                    'name'            => 'ITEM #'.$limit,
                    'contact_id'      => wa()->getUser()->getId(),
                    'create_datetime' => date('Y-m-d H:i:s'),
                ]
            );

            if ($item->save()) {
                echo sprintf("Item '%s' created\n", $item->name);
            }
        }
    }
}
