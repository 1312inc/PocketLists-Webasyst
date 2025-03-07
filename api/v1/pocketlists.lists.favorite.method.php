<?php

class pocketlistsListsFavoriteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_POST;

    public function execute()
    {
        $data = $this->readBodyAsJson();

        if (empty($data)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($data)) {
            throw new pocketlistsApiException(_w('Type error data'), 400);
        }

        $list_ids = array_unique(array_column($data, 'id'));
        if (empty($list_ids)) {
            throw new pocketlistsApiException(_w('Lists not found'), 404);
        }

        /** @var pocketlistsListModel $list_model */
        $list_model = pl2()->getModel(pocketlistsList::class);
        $lists_in_db = $list_model->select('id, key_item_id')->where('id IN (i:list_ids) AND archived = 0', ['list_ids' => $list_ids])->fetchAll('id');
        $current_user_id = $this->getUser()->getId();
        $list_id_available = pocketlistsRBAC::getAccessListForContact($current_user_id);

        foreach ($data as &$_data) {
            /** set default */
            $list_id = ifset($_data, 'id', null);
            $_data = [
                'id'         => $list_id,
                'item_id'    => ifset($lists_in_db, $list_id, 'key_item_id', null),
                'contact_id' => $current_user_id,
                'favorite'   => ifset($_data, 'favorite', false),
                'success'    => true,
                'errors'     => []
            ];

            if (empty($list_id)) {
                $_data['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($list_id)) {
                $_data['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            }

            if (!array_key_exists($list_id, $lists_in_db)) {
                $_data['errors'][] = _w('List not found');
            }  elseif (!in_array($lists_in_db[$list_id]['id'], $list_id_available)) {
                $_data['errors'][] = _w('List access denied');
            }

            if (!empty($_data['errors'])) {
                $_data['success'] = false;
            }
        }

        $data_ok = array_filter($data, function ($l) {
            return $l['success'];
        });
        $err_favorite = array_diff_key($data, $data_ok);
        $set_favorite = array_filter($data_ok, function ($f) {
            return $f['favorite'];
        });
        $unset_favorite = array_filter($data_ok, function ($f) {
            return !$f['favorite'];
        });

        $uf_model = pl2()->getModel(pocketlistsUserFavorites::class);
        if (!empty($set_favorite)) {
            $uf_model->multipleInsert($set_favorite, waModel::INSERT_IGNORE);
        }
        if (!empty($unset_favorite)) {
            $uf_model->exec("
                DELETE FROM pocketlists_user_favorites
                WHERE contact_id = i:contact_id AND item_id IN (i:item_ids)
            ", [
                'contact_id' => $current_user_id,
                'item_ids'   => array_column($unset_favorite, 'item_id')
            ]);
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($set_favorite, $unset_favorite, $err_favorite),
            ['id', 'favorite'],
            ['id' => 'int', 'favorite' => 'bool']
        );
    }
}
