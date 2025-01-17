<?php

class pocketlistsFileDownloadController extends waController
{
    public function execute()
    {
        $id = (int) $this->getRequest()->param('id', 0, waRequest::TYPE_INT);

        if ($id < 1) {
            throw new pocketlistsNotFoundException();
        }

        $file = $this->getAttachment($id);
        if (!$file) {
            throw new pocketlistsNotFoundException();
        }

        if (!$this->getUser()->isAdmin(pocketlistsHelper::APP_ID)) {
            $user_id = $this->getUser()->getId();
            $list_id_available = pocketlistsRBAC::getAccessListForContact();
            if (empty($file['list_id'])) {
                if ($user_id != $file['contact_id']) {
                    throw new pocketlistsForbiddenException();
                }
            } elseif (!in_array($file['list_id'], $list_id_available)) {
                throw new pocketlistsForbiddenException();
            }
        }

        wa()->getResponse()->addHeader('Cache-Control', 'private, no-transform');

        if ($file['storage'] === 'public') {
            $path_public = wa()->getDataUrl('attachments/%s/%s', true, pocketlistsHelper::APP_ID, true);
            $this->redirect(sprintf($path_public, $file['item_id'], $file['filename']));
        }

        $path_private = wa()->getDataPath('attachments/%s/%s', false, pocketlistsHelper::APP_ID);
        waFiles::readFile(sprintf($path_private, $file['item_id'], $file['filename']));
    }

    private function getAttachment($attachement_id)
    {
        return pl2()->getModel()->query("
            SELECT pa.*, pi2.list_id, pi2.contact_id FROM pocketlists_attachment pa
            LEFT JOIN pocketlists_item pi2 ON pa.item_id = pi2.id
            WHERE pa.id = i:attachement_id;
            ",
            ['attachement_id' => $attachement_id]
        )->fetchAssoc();
    }
}
