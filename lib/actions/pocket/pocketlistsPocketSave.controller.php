<?php

class pocketlistsPocketSaveController extends  waJsonController
{
    public function execute()
    {
        $pocket = waRequest::post('pocket', array(), waRequest::TYPE_ARRAY);
        $pm = new pocketlistsPocketModel();
        if (!$pocket['id']) {
            unset($pocket['id']);
        }
        $id = $pm->insert($pocket, 1);
        // add full rights for this pocket
        $rm = new waContactRightsModel();
        $rm->save(wa()->getUser()->getId(), wa()->getApp(), 'pocket.'.$id, 1);
        // todo: update access rights for others
        $this->response = array(
            'id' => isset($pocket['id']) ? $pocket['id'] : $id,
            'new' => isset($pocket['id']) ? 0 : 1
        );
    }
}