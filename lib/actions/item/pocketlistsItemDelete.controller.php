<?php

/**
 * Class pocketlistsItemDeleteController
 */
class pocketlistsItemDeleteController extends pocketlistsJsonController
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $item = $this->getItem();

        // todo: child-free
        /*if ($item['has_children']) {
            $tree = $im->getAllByList($item['list_id'], $id);
            pocketlistsHelper::getItemChildIds($item['id'], $tree[$item['id']], $delete_ids);
        } else {
            pocketlistsHelper::getItemChildIds($item['id'], array('id' => $item['id'], 'childs' => array()), $delete_ids);
        }*/

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $itemFactory->delete($item);

        /** @var pocketlistsAttachmentFactory $attachmentFactory */
        $attachmentFactory = pl2()->getEntityFactory(pocketlistsAttachment::class);
        $attachmentFactory->deleteAllByItem($item);

        $this->logService->add(
            $this->logService->getFactory()->createNewAfterItemDelete(
                (new pocketlistsLogContext())->setItem($item)
            )
        );

        $this->response = ['id' => $item->getId()];
    }
}
