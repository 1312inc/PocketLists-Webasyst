<?php

class pocketlistsListsCloneMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_GET;

    public function execute()
    {
        $list_id = $this->get('list_id', true);
        $list_name = $this->get('list_name');
        $pocket_id = $this->get('pocket_id');
        if (!pocketlistsViewHelper::isPremium()) {
            throw new pocketlistsApiException(_w('Payment Required'), 402);
        } elseif (!is_numeric($list_id)) {
            throw new pocketlistsApiException(_w('Type error `list_id`'), 400);
        }

        $list = (new pocketlistsListModel())->getById($list_id);
        if (!$list) {
            throw new pocketlistsApiException(_w('List not found'), 404);
        }

        $lists_id_available = pocketlistsRBAC::getAccessListForContact($this->getUser());
        if (!in_array($list_id, $lists_id_available)) {
            throw new pocketlistsApiException(_w('List access denied'), 403);
        }

        if ($list_name) {
            if (!is_string($list_name)) {
                throw new pocketlistsApiException(_w('Type error `list_name`'), 400);
            }
            $list['name'] = $list_name;
        }

        if ($pocket_id) {
            if (!is_numeric($pocket_id)) {
                throw new pocketlistsApiException(_w('Type error `pocket_id`'), 400);
            }
            $pocket = (new pocketlistsPocketModel())->getById($pocket_id);
            if (!$pocket) {
                throw new pocketlistsApiException(_w('Pocket not found'), 404);
            }
            $list['pocket_id'] = $pocket_id;
        }

        $errors = [];
        $clone_list = [];
        try {
            $clone_list = $this->cloneList($list);
        } catch (Exception $e) {
            $errors[] = ['code' => $e->getCode(), 'text' => $e->getMessage()];
        }

        try {
            $this->cloneItems($clone_list);
        } catch (Exception $e) {
            $errors[] = ['code' => $e->getCode(), 'text' => $e->getMessage()];
        }

        $this->response['data'] = [[
            'success' => !!empty($errors),
            'errors' => $errors,
            'data' => $clone_list
        ]];
    }


    /**
     * @param $list
     * @return array|mixed
     * @throws waDbException
     * @throws waException
     */
    private function cloneList($list = [])
    {
        $model = pl2()->getModel(pocketlistsItem::class);
        $key_item = $model->getById($list['key_item_id']);

        /** @var pocketlistsListFactory $list_factory */
        $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

        $list += $key_item;
        $list['old_id'] = $list['id'];
        $list['activity_datetime'] = date('Y-m-d H:i:s');
        $list['archived'] = 0;
        $list['template'] = 0;
        $list['complete_datetime'] = null;
        $list['uuid'] = waString::uuid();
        unset($list['id'], $list['key_item_id']);


        $list_entity = $list_factory->generateWithData($list);
        if ($list_factory->save($list_entity)) {
            $list['id'] = $list_entity->getId();
        } else {
            pocketlistsLogger::error('Error clone list. Data list: '.var_export($list, true), 'clone.log');
            return [];
        }

        $cr_model = new waContactRightsModel();
        $right_lists = $cr_model->select('*')->where('app_id = ? AND name = ?', [pocketlistsHelper::APP_ID, 'list.'.$list['old_id']])->fetchAll();
        foreach ($right_lists as &$r_list) {
            $old_right_list_id = explode('.', $r_list['name'])[1];
            if ($old_right_list_id == $list['old_id']) {
                $r_list['name'] = 'list.'.$list['id'];
            }
        }
        unset($r_list);

        if (!empty($right_lists)) {
            $cr_model->multipleInsert($right_lists);
        }

        return $list;
    }

    /**
     * @param $clone_list
     * @return void
     * @throws waException
     */
    private function cloneItems($clone_list)
    {
        $model = pl2()->getModel(pocketlistsItem::class);
        try {
            $items = $model->select('*')->where('list_id = ?', $clone_list['old_id'])->fetchAll();
            foreach ($items as &$_item) {
                $_item['old_id'] = $_item['id'];
                $_item['list_id'] = $clone_list['id'];
                $_item['uuid'] = waString::uuid();
                $_item['status'] = pocketlistsItem::STATUS_UNDONE;
                $_item['create_datetime'] = date('Y-m-d H:i:s');
                $_item['activity_datetime'] = null;
                $_item['complete_datetime'] = null;
                $_item['complete_contact_id'] = null;
                unset($_item['id']);
            }

            $item_model = pl2()->getModel(pocketlistsItem::class);
            $result = $item_model->multipleInsert($items);
            if ($result->getResult()) {
                $last_id = $result->lastInsertId();
                $rows_count = $result->affectedRows();
                if ($rows_count === count($items)) {
                    foreach ($items as &$_item) {
                        $_item['id'] = $last_id++;
                        $this->cloneAttachments($_item['old_id'], $_item['id']);
                    }
                }
            }
        } catch (Exception $e) {
            throw new pocketlistsApiException('Error clone items. Data list: '.var_export($clone_list, true).' Exception: '.$e->getMessage(), 400);
        }
    }

    /**
     * @param $old_item_id
     * @param $new_item_id
     * @return void
     * @throws waException
     */
    private function cloneAttachments($old_item_id, $new_item_id)
    {
        $wa_data_path = wa()->getDataPath(pocketlistsUploadedFileVO::PATH, false, pocketlistsHelper::APP_ID);
        $model = pl2()->getModel(pocketlistsAttachment::class);
        $attachments = $model->select('*')->where('item_id = ?', $old_item_id)->fetchAll();

        if (empty($attachments)) {
            return;
        }
        try {
            waFiles::copy($wa_data_path.DIRECTORY_SEPARATOR.$old_item_id, $wa_data_path.DIRECTORY_SEPARATOR.$new_item_id);

            foreach ($attachments as &$_attachment) {
                $_attachment['item_id'] = $new_item_id;
                $_attachment['uuid'] = waString::uuid();
                unset($_attachment['id']);
            }
            $result = $model->multipleInsert($attachments);
            if ($result->getResult()) {
                $last_id = $result->lastInsertId();
                $rows_count = $result->affectedRows();
                if ($rows_count === count($attachments)) {
                    foreach ($attachments as &$_attachment) {
                        $_attachment['id'] = $last_id++;
                    }
                }
            }
        } catch (Exception $e) {
            pocketlistsLogger::error('Error clone attachment directory. Data attachments: '.var_export($attachments, true).' Exception: '.$e->getMessage());
        }
    }
}
