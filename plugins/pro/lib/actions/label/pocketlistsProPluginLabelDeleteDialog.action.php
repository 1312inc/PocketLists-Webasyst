<?php

/**
 * Class pocketlistsProPluginLabelDeleteDialogAction
 */
class pocketlistsProPluginLabelDeleteDialogAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function runAction($params = null)
    {
        /** @var pocketlistsProPluginLabel $label */
        $label = pl2()->getEntityFactory(pocketlistsProPluginLabel::class)->findById($this->getId());

        if (!$label instanceof pocketlistsProPluginLabel) {
            throw new pocketlistsNotFoundException('Label not found');
        }

        /** @var pocketlistsProPluginLabelModel $labelsModel */
        $labelsModel = pl2()->getModel(pocketlistsProPluginLabel::class);
        $this->view->assign(
            [
                'id'                   => $label->getId(),
                'itemsWithLabelsCount' => $labelsModel->countItemsWithLabel($label->getId()),
            ]
        );
    }
}
