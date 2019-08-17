<?php

/**
 * Class pocketlistsProPluginLabelPocketAction
 */
class pocketlistsProPluginLabelPocketAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsAssertException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $pocketId = waRequest::get('pocket_id', 0, waRequest::TYPE_INT);
        pocketlistsAssert::gt($pocketId, 0);

        $labelId = waRequest::get('label_id', 0, waRequest::TYPE_INT);
        pocketlistsAssert::gte($labelId, 0);

        /** @var pocketlistsProPluginLabelFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);

        $resolver = new pocketlistsEntityResolver();
        /** @var pocketlistsPocket $pocket */
        $pocket = $resolver->getEntityById(pocketlistsPocket::class, $pocketId);
        /** @var pocketlistsProPluginLabel $label */
        if ($labelId) {
            $label = $resolver->getEntityById(pocketlistsProPluginLabel::class, $labelId);
            $items = $factory->findItemsByPocketAndLabel($pocket, $label);
        } else {
            $label = $factory->createNewDone();
            $items = pl2()->getEntityFactory(pocketlistsItem::class)
                ->setOffset(0)
                ->setLimit(400)
                ->findLogbookForPocket($pocket, null, false, true);
        }

        $this->view->assign(
            [
                'label'  => $label,
                'pocket' => $pocket,
                'items'  => $items,
                'empty'  => count($items),
            ]
        );
    }
}