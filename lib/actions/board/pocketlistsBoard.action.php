<?php

/**
 * Class pocketlistsBoardAction
 */
class pocketlistsBoardAction extends pocketlistsViewAction
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
        if (wa()->whichUI() === '1.3') {
            if (!waRequest::isXMLHttpRequest()) {
                $this->redirect(wa()->getAppUrl(null, true));
            }
        }
        $pocketId = waRequest::param('pocket_id', 0, waRequest::TYPE_INT);

        /** @var pocketlistsLabelFactory $labelFactory */
        $labelFactory = pl2()->getEntityFactory(pocketlistsLabel::class);
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
            $labelItems = new pocketlistsLabelItemsDto();
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

        $labelItems = new pocketlistsLabelItemsDto();
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

        if (wa()->whichUI() !== '1.3') {
            $this->setLayout(new pocketlistsStaticLayout());
        }
        $this->setTemplate(wa()->getAppPath(sprintf('templates/actions%s/board/Board.html', pl2()->getUI2TemplatePath())));


        $this->view->assign([
            'pockets'  => $pocketFactory->findAllForUser(),
            'pocket'   => $pocket ?: $pocketFactory->createNew(),
            'allItems' => $allItems
        ]);
    }
}
