<?php

/**
 * Class pocketlistsProPluginLabelSaveOrderController
 */
class pocketlistsProPluginLabelSaveOrderController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $labelIds = waRequest::post('data', [], waRequest::TYPE_ARRAY);

        if ($labelIds) {
            /** @var pocketlistsProPluginLabelModel $labelModel */
            $labelModel = pl2()->getModel(pocketlistsProPluginLabel::class);
            $this->response = $labelModel->updateSort($labelIds);
        }
    }
}
