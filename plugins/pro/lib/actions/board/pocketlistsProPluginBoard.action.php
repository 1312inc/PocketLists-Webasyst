<?php

/**
 * Class pocketlistsProPluginBoardAction
 */
class pocketlistsProPluginBoardAction extends pocketlistsProPluginAbstractViewAction
{
    const ON_PAGE = 99;

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

        foreach ($allLabels as $label) {
            $labelItems = new pocketlistsProPluginLabelItemsDto();
            $labelItems->label = $label;
            $labelItems->items = $labelFactory
                ->setOffset(0)
                ->setLimit(self::ON_PAGE)
                ->findItemsByLabelAndPocket($label, $pocket);
            $labelItems->count = $labelFactory->getLastFoundCount();
            if ($labelItems->count > 99) {
                $labelItems->count = '99+';
            }

            $allItems[$labelItems->label->getId()] = $labelItems;
        }

        $labelItems = new pocketlistsProPluginLabelItemsDto();
        $labelItems->label = $labelFactory->createNewDone();
        $labelItems->items = $labelFactory
            ->setOffset(0)
            ->setLimit(self::ON_PAGE)
            ->findDoneItemsByAllLabelsAndPocket();
        $labelItems->count = $labelFactory->getLastFoundCount();
        if ($labelItems->count > 99) {
            $labelItems->count = '99+';
        }
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
