<?php

/**
 * Class pocketlistsPocketSaveController
 */
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

        if ($pocketData['id']) {
            $pocket = pocketlistsPocketModel::model()->findByPk($pocketData['id']);
            if (!$pocket) {
                $this->setError('no pocket');

                return;
            }
            unset($pocketData['id']);

            $pocket->setAttributes($pocketData);
            if (!$pocket->save()) {
                $this->setError('some error on save pocket');

                return;
            }
        } else {
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
            } else {
                $this->setError('some error on save pocket');

                return;
            }
        }

        $this->response = $pocket->getAttributes();
    }
}

