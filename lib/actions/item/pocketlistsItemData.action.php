<?php

/**
 * Class pocketlistsItemDataAction
 */
class pocketlistsItemDataAction extends waViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        if (waRequest::getMethod() == 'post') {
            $item_new_data = waRequest::post('item', [], waRequest::TYPE_ARRAY);
            $im = new pocketlistsItemModel();
            $item_from_db = isset($item_new_data['id'])
                ? $im->getById($item_new_data['id'])
                : new pocketlistsItemModel($item_new_data);

            if ($item_new_data) {
                $item_new_data['id'] = $item_from_db['id'];
                $item_new_data['list_id'] = $item_new_data['list_id'] === ''
                    ? null
                    : $item_new_data['list_id']; // do not update list_id for items from NULL-list

                // move item's children to another list
                $move_ids = [];
                if ($item_from_db['has_children'] && $item_from_db['list_id'] != $item_new_data['list_id']) {
                    $tree = $im->getAllByList($item_from_db['list_id'], true);

                    pocketlistsHelper::getItemChildIds($item_new_data['id'], $tree[$item_new_data['id']], $move_ids);

                    $im->updateById(
                        $move_ids,
                        [
                            'list_id'         => $item_new_data['list_id'],
                            'update_datetime' => date("Y-m-d H:i:s"),
                        ]
                    );
                }

                pocketlistsHelper::getDueDatetime($item_new_data);
                $item_new_data['assigned_contact_id'] = !empty($item_new_data['assigned_contact_id'])
                    ? (int)$item_new_data['assigned_contact_id']
                    : null;
                $item_new_data['update_datetime'] = date("Y-m-d H:i:s");
                $im->addCalculatedPriorityDataAndSave($item_new_data['id'], $item_new_data);

                if ($item_new_data['assigned_contact_id']
                    && $item_new_data['assigned_contact_id'] != $item_from_db['assigned_contact_id']) {
                    $this->logAction(
                        pocketlistsLogAction::ITEM_ASSIGN,
                        [
                            'list_id'     => $item_new_data['list_id'],
                            'assigned_to' => $item_new_data['assigned_contact_id'],
                        ]
                    );
                }

                $this->view->assign(
                    'pl2_attachments_path',
                    wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID)
                );
                $item = $im->skipCache()->getById($item_new_data['id']);
                $item = $im->extendItemData($item);
                $this->view->assign('item', $item);
            }
        }
    }

    private function saveAttachment($item)
    {
        $path_public = wa()->getDataPath('attachments/'.$item['id'].'/', true, pocketlistsHelper::APP_ID);
        if (is_writable($path_public)) {
            $errors = [];
            $f = waRequest::file('attachment');
            $name = $f->name;
            if ($f->uploaded()) {
                if (!preg_match('//u', $name)) {
                    $tmp_name = @iconv('windows-1251', 'utf-8//ignore', $name);
                    if ($tmp_name) {
                        $name = $tmp_name;
                    }
                }
                if (file_exists($path_public.DIRECTORY_SEPARATOR.$name)) {
                    $i = strrpos($name, '.');
                    $ext = substr($name, $i + 1);
                    $name = substr($name, 0, $i);
                    $i = 1;
                    while (file_exists($path_public.DIRECTORY_SEPARATOR.$name.'-'.$i.'.'.$ext)) {
                        $i++;
                    }
                    $name = $name.'-'.$i.'.'.$ext;
                }
                $type = null;
                if (exif_imagetype($f->tmp_name)) {
                    $type = 'image';
                }
                if ($f->moveTo($path_public, $name)) {
                    $pa = new pocketlistsAttachmentModel();
                    $pa->insert(
                        [
                            'item_id'  => $item['id'],
                            'filename' => $name,
                            'filetype' => $type,
                        ]
                    );
                } else {
                    $errors[] = sprintf(_w('Failed to upload file %s.'), $f->name);
                }
            } else {
                $errors[] = sprintf(_w('Failed to upload file %s.'), $f->name).' ('.$f->error.')';
            }
        }
    }

    private function deleteAttachments($item)
    {
        $to_delete = waRequest::post('attachment_delete', false);
        if ($to_delete) {
            $am = new pocketlistsAttachmentModel();
            $am->remove($item['id'], $to_delete);
        }
    }
}
