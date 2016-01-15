<?php

class pocketlistsItemDataAction extends waViewAction
{
    public function execute()
    {
        if (waRequest::getMethod() == 'post') {
            $item = waRequest::post('item', array(), waRequest::TYPE_ARRAY);
            if ($item) {
                $this->saveAttachment($item);
                $this->deleteAttachmnets($item);
                $im = new pocketlistsItemModel();

                pocketlistsHelper::getDueDatetime($item);
                $item['assigned_contact_id'] = $item['assigned_contact_id'] ? $item['assigned_contact_id'] : null;
                $item['update_datetime'] = date("Y-m-d H:i:s");
                $im->updateWithCalcPriority($item['id'], $item);
                $this->view->assign('item', $im->getById($item['id']));
            }
        }
    }

    private function saveAttachment($item)
    {
        $path_private = wa()->getDataPath('attachments/'.$item['id'].'/');
        if (is_writable($path_private)) {
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
                if (file_exists($path_private.DIRECTORY_SEPARATOR.$name)) {
                    $i = strrpos($name, '.');
                    $ext = substr($name, $i + 1);
                    $name = substr($name, 0, $i);
                    $i = 1;
                    while (file_exists($path_private.DIRECTORY_SEPARATOR.$name.'-'.$i.'.'.$ext)) {
                        $i++;
                    }
                    $name = $name.'-'.$i.'.'.$ext;
                }
                if ($f->moveTo($path_private, $name)) {
                    $pa = new pocketlistsAttachmentModel();
                    $pa->insert(array(
                        'item_id' => $item['id'],
                        'filename' => $name
                    ));
                } else {
                    $errors[] = sprintf(_w('Failed to upload file %s.'), $f->name);
                }
            } else {
                $errors[] = sprintf(_w('Failed to upload file %s.'), $f->name).' ('.$f->error.')';
            }
        }
    }

    private function deleteAttachmnets($item)
    {
        $to_delete = waRequest::post('attachment_delete', false);
        if ($to_delete) {
            $am = new pocketlistsAttachmentModel();
            $am->delete($item['id'], $to_delete);
        }
    }
}
