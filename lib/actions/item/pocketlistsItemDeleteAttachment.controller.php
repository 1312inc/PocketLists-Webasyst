<?php

/**
 * Class pocketlistsItemDeleteAttachmentController
 */
class pocketlistsItemDeleteAttachmentController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $attachment = waRequest::post('attachment', [], waRequest::TYPE_ARRAY_TRIM);
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);

        $item = $this->getItem($item_id);

        if ($attachment) {
            /** @var pocketlistsAttachmentFactory $attachmentFactory */
            $attachmentFactory = pl2()->getEntityFactory(pocketlistsAttachment::class);
            $attachmentFactory->deleteAllByItemAndNames($item, $attachment);
        } else {
            $this->setError('no attachments');
        }
    }
}
