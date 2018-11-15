<?php

class pocketlistsPocketSortController extends waJsonController
{
    public function execute()
    {
        $data = waRequest::post('data', false);
        if ($data) {
            $pm = new pocketlistsPocketModel();
            foreach ($data as $pocket) {
                $pm->updateById($pocket['id'], ['sort' => $pocket['sort']]);
            }
        }
    }
}
