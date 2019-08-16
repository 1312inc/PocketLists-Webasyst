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
        pocketlistsAssert::gt($labelId, 0);

        $resolver = new pocketlistsEntityResolver();
        /** @var pocketlistsPocket $pocket */
        $pocket = $resolver->getEntityById(pocketlistsPocket::class, $pocketId);
        /** @var pocketlistsProPluginLabel $label */
        $label = $resolver->getEntityById(pocketlistsProPluginLabel::class, $labelId);

        /** @var pocketlistsProPluginLabelFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);

        $items = $factory->findItemsByPocketAndLabel($pocket, $label);

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