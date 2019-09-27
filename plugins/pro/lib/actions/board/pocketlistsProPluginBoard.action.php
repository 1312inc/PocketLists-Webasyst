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
        $pocketId = waRequest::request('pocket_id', 0, waRequest::TYPE_INT);

        /** @var pocketlistsProPluginLabelFactory $labelFactory */
        $labelFactory = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
        $allItems = [];
        $pocket = null;
        $allLabels = $labelFactory->findAll();

        if ($pocketId) {
            /** @var pocketlistsPocket $pocket */
            $pocket = (new pocketlistsEntityResolver())->getEntityById(pocketlistsPocket::class, $pocketId);

            if (!pocketlistsRBAC::contactHasAccessToPocket($pocket)) {
                throw new pocketlistsForbiddenException();
            }
        }

        /** @var pocketlistsProPluginLabel $label */
        foreach ($allLabels as $label) {
            $labelItems = new pocketlistsProPluginLabelItemsDto();
            $labelItems->label = $label;
            $labelItems->items = $labelFactory
                ->setOffset(0)
                ->setLimit(50)
                ->findItemsByLabelAndPocket($label, $pocket);
            $labelItems->count = count($labelItems->items);

            $allItems[$labelItems->label->getId()] = $labelItems;
        }

        $labelItems = new pocketlistsProPluginLabelItemsDto();
        $labelItems->label = $labelFactory->createNewDone();
        $labelItems->items = $labelFactory
            ->setOffset(0)
            ->setLimit(50)
            ->findDoneItemsByAllLabelsAndPocket();
        $labelItems->count = count($labelItems->items);
        $labelItems->isDone = true;

        $allItems[$labelItems->label->getId()] = $labelItems;

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);

        $this->view->assign(
            [
                'pockets'  => $pocketFactory->findAllForUser(),
                'pocket'   => $pocket ?: $pocketFactory->createNew(),
                'allItems' => $allItems,
            ]
        );
    }
}
