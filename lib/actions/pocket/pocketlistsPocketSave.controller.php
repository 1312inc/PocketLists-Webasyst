<?php

class pocketlistsPocketSaveController extends waJsonController
{
    /**
     * @throws waDbException
     */
    public function execute()
    {
        $pocket = waRequest::post('pocket', [], waRequest::TYPE_ARRAY);
        if (!$pocket['id']) {
            unset($pocket['id']);
        }

        /** @var pocketlistsPocketModel $pocket */
        $pocket = (new pocketlistsPocketModel($pocket))->save();

        // add full rights for this pocket
        (new waContactRightsModel())->save(
            wa()->getUser()->getId(),
            wa()->getApp(),
            'pocket.'.$pocket->pk,
            1
        );
        // todo: update access rights for others

        $this->response = $pocket->getAttributes();
    }
}
