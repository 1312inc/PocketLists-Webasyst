<?php

/**
 * Class pocketlistsLabelSaveOrderController
 */
class pocketlistsLabelSaveOrderController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $labelIds = waRequest::post('data', [], waRequest::TYPE_ARRAY);

        if ($labelIds) {
            /** @var pocketlistsLabelModel $labelModel */
            $labelModel = pl2()->getModel(pocketlistsLabel::class);
            $this->response = $labelModel->updateSort($labelIds);
        }
    }
}
