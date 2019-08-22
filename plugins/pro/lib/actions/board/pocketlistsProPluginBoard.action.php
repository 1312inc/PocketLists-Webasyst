<?php

/**
 * Class pocketlistsProPluginBoardAction
 */
class pocketlistsProPluginBoardAction extends pocketlistsProPluginAbstractViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        /** @var pocketlistsProPluginLabelFactory $labelFactory */
        $labelFactory = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
        $allLabels = $labelFactory->findAll();

        $allItems = [];
        /** @var pocketlistsProPluginLabel $label */
        foreach ($allLabels as $label) {
            $labelItems = new pocketlistsProPluginLabelItemsDto();
            $labelItems->label = $label;
            $labelItems->items = $labelFactory
                ->setOffset(0)
                ->setLimit(50)
                ->findItemsByLabel($label);
            $labelItems->count = count($labelItems->items);

            if ($labelItems->count) {
                $allItems[$labelItems->label->getId()] = $labelItems;
            }
        }

        $labelItems = new pocketlistsProPluginLabelItemsDto();
        $labelItems->label = $labelFactory->createNewDone();
        $labelItems->items = $labelFactory
            ->setOffset(0)
            ->setLimit(50)
            ->findDoneItemsByAllLabels();
        $labelItems->count = count($labelItems->items);
        $labelItems->isDone = true;

        if ($labelItems->count) {
            $allItems[$labelItems->label->getId()] = $labelItems;
        }

        $this->view->assign(
            [
                'allItems' => $allItems,
            ]
        );
    }
}
