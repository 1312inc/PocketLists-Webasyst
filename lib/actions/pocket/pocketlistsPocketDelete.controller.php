<?php

class pocketlistsPocketDeleteController extends waJsonController
{
    public function execute()
    {
        $pocket_id = waRequest::post('id', 0, waRequest::TYPE_INT);

        if ($pocket_id) {
            $pm = new pocketlistsPocketModel();
            $available_pockets = pocketlistsHelper::getAccessPocketForContact();
            if (!in_array($pocket_id, $available_pockets)) {
                throw new waException('Access denied.', 403);
            }

            $this->response = $pm->deleteAll($pocket_id);
        } else {
            $this->errors = 'no pocket id';
        }
    }
}
