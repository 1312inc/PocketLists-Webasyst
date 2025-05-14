<?php

/**
 * Class pocketlistsLabelAddToItemController
 */
class pocketlistsLabelAddToItemController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $resolver = new pocketlistsEntityResolver();

        $labelId = waRequest::post('id', 0, waRequest::TYPE_INT);

        /** @var pocketlistsLabel $label */
        if ($labelId) {
            $label = $resolver->getEntityById(pocketlistsLabel::class, $labelId);
        } else {
            $label = pl2()->getEntityFactory(pocketlistsLabel::class)->createNoStatus();
        }

        /** @var pocketlistsItem $item */
        $item = $resolver->getEntityById(
            pocketlistsItem::class,
            waRequest::post('item_id', 0, waRequest::TYPE_INT)
        );

        $label->assignToItem($item);

        pl2()->getEntityFactory(pocketlistsItem::class)->update($item);

        /** @var pocketlistsLogFactory $factory */
        $factory = pl2()->getEntityFactory('pocketlistsLog');
        $this->logService->add(
            $factory->createNewAfterAddLabel(
                (new pocketlistsLogContext())
                    ->setItem($item)
                    ->setAction(pocketlistsLog::ACTION_UPDATE),
                $label
            )
        );
    }
}
