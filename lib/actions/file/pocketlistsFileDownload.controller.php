<?php

class pocketlistsFileDownloadController extends waController
{
    public function execute()
    {
        $id = (int) $this->getRequest()->param('id', 0, waRequest::TYPE_INT);

        if ($id < 1) {
            throw new pocketlistsNotFoundException();
        }

        $file = pl2()->getModel(pocketlistsAttachment::class)->getById($id);
        if (!$file) {
            throw new pocketlistsNotFoundException();
        }

        wa()->getResponse()->addHeader('Cache-Control', 'private, no-transform');

        if ($file['storage'] === 'public') {
            $path_public = wa()->getDataUrl('attachments/%s/%s', true, pocketlistsHelper::APP_ID, true);
            $this->redirect(sprintf($path_public, $file['item_id'], $file['filename']));
        }

        $path_private = wa()->getDataPath('attachments/%s/%s', false, pocketlistsHelper::APP_ID);

        waFiles::readFile(sprintf($path_private, $file['item_id'], $file['filename']));
    }
}
