<?php

class pocketlistsPocketSaveController extends waJsonController
{
    /**
     * @throws waDbException
     */
    public function execute()
    {
        $pocketData = waRequest::post('pocket', [], waRequest::TYPE_ARRAY);
        if (!$pocketData['id']) {
            unset($pocketData['id']);
        }

        $pocket = new pocketlistsPocketModel($pocketData);

        if ($pocket->save()) {

            // add full rights for this pocket
            (new waContactRightsModel())->save(
                wa()->getUser()->getId(),
                wa()->getApp(),
                'pocket.'.$pocket->pk,
                1
            );
            // todo: update access rights for others

            $this->response = $pocket->getAttributes();
        } else {
            $this->setError('some error on save pocket');
        }
    }
}
