<?php

class pocketlistsItemDataAction extends waViewAction
{
    public function execute()
    {
        if (waRequest::getMethod() == 'post') {
            $item_new_data = waRequest::post('item', array(), waRequest::TYPE_ARRAY);
            $im = new pocketlistsItemModel();
            $item_from_db = $im->getById($item_new_data['id']);
            if ($item_new_data && $item_from_db) {
                $item_new_data['id'] = $item_from_db['id'];
                $item_new_data['list_id'] = $item_new_data['list_id'] === "" ?
                    null : $item_new_data['list_id']; // do not update list_id for items from NULL-list

                // move item's children to another list
                $move_ids = array();
                if ($item_from_db['has_children'] && $item_from_db['list_id'] != $item_new_data['list_id']) {
                    $tree = $im->getAllByList($item_from_db['list_id'], true);
                    pocketlistsHelper::getItemChildIds($item_new_data['id'], $tree[$item_new_data['id']], $move_ids);
                    $im->updateById($move_ids, array(
                        'list_id' =>  $item_new_data['list_id'],
                        'update_datetime' => date("Y-m-d H:i:s")
                    ));
                }

                $this->saveAttachment($item_new_data);
                $this->deleteAttachments($item_new_data);

                pocketlistsHelper::getDueDatetime($item_new_data);
                $item_new_data['assigned_contact_id'] = $item_new_data['assigned_contact_id'] ? $item_new_data['assigned_contact_id'] : null;
                $item_new_data['update_datetime'] = date("Y-m-d H:i:s");
                $im->updateWithCalcPriority($item_new_data['id'], $item_new_data);

                if ($item_new_data['assigned_contact_id']) {
                    $this->logAction(pocketlistsLogAction::ITEM_ASSIGN, array(
                        'list_id' => $item_new_data['list_id'],
                        'assigned_to' => $item_new_data['assigned_contact_id']
                    ));
                }

                $this->view->assign('attachments_path', wa()->getDataUrl('attachments/', true));
                $this->view->assign('item', $im->getById($item_new_data['id']));
            }
        }
    }

    private function saveAttachment($item)
    {
        $path_public = wa()->getDataPath('attachments/'.$item['id'].'/', true);
        if (is_writable($path_public)) {
            $errors = array();
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
                    $pa->insert(array(
                        'item_id' => $item['id'],
                        'filename' => $name,
                        'filetype' => $type
                    ));
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
            $am->delete($item['id'], $to_delete);
        }
    }
}
