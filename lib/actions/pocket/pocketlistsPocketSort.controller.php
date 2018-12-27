<?php

class pocketlistsPocketSortController extends waJsonController
{
    public function execute()
    {
        if (!pocketlistsRBAC::isAdmin()) {
            $this->setError(_w('Access denied'), 403);

            return;
        }

        $data = waRequest::post('data', false);
        if ($data) {
            $pm = new pocketlistsPocketModel();
            foreach ($data as $pocket) {
                $pm->updateById($pocket['id'], ['sort' => $pocket['sort']]);
            }
        }
    }
}
