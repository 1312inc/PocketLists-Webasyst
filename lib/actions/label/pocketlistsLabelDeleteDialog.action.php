<?php

/**
 * Class pocketlistsLabelDeleteDialogAction
 */
class pocketlistsLabelDeleteDialogAction extends pocketlistsViewAction
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
        /** @var pocketlistsLabel $label */
        $label = pl2()->getEntityFactory(pocketlistsLabel::class)->findById($this->getId());

        if (!$label instanceof pocketlistsLabel) {
            throw new pocketlistsNotFoundException('Label not found');
        }

        $this->setTemplate('templates/actions/settings/SettingsLabelDeleteDialog.html');

        /** @var pocketlistsLabelModel $labelsModel */
        $labelsModel = pl2()->getModel(pocketlistsLabel::class);
        $this->view->assign([
            'id'                   => $label->getId(),
            'itemsWithLabelsCount' => $labelsModel->countItemsWithLabel($label->getId()),
        ]);
    }
}