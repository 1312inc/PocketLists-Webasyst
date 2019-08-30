<?php

/**
 * Class pocketlistsProPluginLabelCountByPocketController
 */
class pocketlistsProPluginLabelCountByPocketController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        /** @var pocketlistsPocket $pocket */
        $pocket = (new pocketlistsEntityResolver())->getEntityById(
            pocketlistsPocket::class,
            waRequest::request('pocket_id')
        );

        /** @var pocketlistsProPluginLabelModel $model */
        $model = pl2()->getModel(pocketlistsProPluginLabel::class);

        $data = $model->getByPocketIdWithCount($pocket->getId());
        $data =  array_combine(
            array_keys($data),
            array_column($data, 'labels_count')
        );
        $data[0] = count(
            pl2()
                ->getModel(pocketlistsItem::class)
                ->getLogbookItems(false, false, true, $pocket->getId(), 0, 100)
        );

        $this->response = $data;
    }
}
