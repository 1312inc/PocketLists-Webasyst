<?php

class pocketlistsItemDeleteAttachmentController extends waJsonController
{
    public function execute()
    {
        $attachment = waRequest::post('attachment', '', waRequest::TYPE_STRING);
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);
        if ($attachment && $item_id) {
            $im = new pocketlistsItemModel();
            $item = $im->getById($item_id);
            if (!$item) {
                $this->setError('no item with such id');
                return;
            }

            $am = new pocketlistsAttachmentModel();
            $am->delete($item['id'], $attachment);
        } else {
            $this->setError('no ids');
        }
    }
}