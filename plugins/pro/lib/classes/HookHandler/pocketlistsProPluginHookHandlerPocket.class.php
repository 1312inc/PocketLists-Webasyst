<?php

/**
 * Class pocketlistsProPluginHookHandlerPocket
 */
class pocketlistsProPluginHookHandlerPocket extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param null|mixed $params
     *
     * @return mixed
     * @throws waException
     */
    public function handle($params = null)
    {
        $return = ['sidebar_section' => ''];

        /** @var pocketlistsPocket $pocket */
        $pocket = $params['pocket'];
        pocketlistsAssert::instance($pocket, pocketlistsPocket::class);

        /** @var pocketlistsProPluginLabelFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);

        $pocketLabelsInfo = [];
        $totalLabels = 0;
        $data = $factory->getModel()->getByPocketIdWithCount($pocket->getId());
        /** @var pocketlistsProPluginLabel $label */
        foreach ($factory->findAll() as $label) {
            $pocketLabelInfo = new pocketlistsProPluginLabelPocketInfoDto();
            $pocketLabelInfo->pocket = $pocket;
            $pocketLabelInfo->count = ifset($data, $label->getId(), 'labels_count', 0);
            $pocketLabelInfo->label = $label;
            $pocketLabelsInfo[] = $pocketLabelInfo;
            $totalLabels += $pocketLabelInfo->count;
        }

        if ($totalLabels) {
            $pocketLabelInfo = new pocketlistsProPluginLabelPocketInfoDto();
            $pocketLabelInfo->pocket = $pocket;
            $pocketLabelInfo->label = $factory->createNewDone();
            $pocketLabelInfo->count = count(
                pl2()
                    ->getModel(pocketlistsItem::class)
                    ->getLogbookItems(false, false, true, $pocket->getId(), 0, 100)
            );

            $pocketLabelsInfo[] = $pocketLabelInfo;
        }

        $this->getView()->assign(
            [
                'pocketLabelStat' => $pocketLabelsInfo,
                'pocket'          => $pocket,
            ]
        );

        $return['sidebar_section'] = $this->getView()->fetch($this->getViewTemplate('backend_pocket.sidebar_section'));

        return $return;
    }
}
