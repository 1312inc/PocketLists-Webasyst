<?php

/**
 * Class pocketlistsItemDeleteAttachmentController
 */
class pocketlistsItemDeleteAttachmentController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function execute()
    {
        $attachmentId = waRequest::post('attachment', [], waRequest::TYPE_INT);
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);

        $item = $this->getItem($item_id);

        if ($item->getListId() && !pocketlistsRBAC::canAccessToList($item->getList())) {
            throw new pocketlistsForbiddenException();
        }

        if ($attachmentId) {
            /** @var pocketlistsAttachmentFactory $attachmentFactory */
            $attachmentFactory = pl2()->getEntityFactory(pocketlistsAttachment::class);
            $attachment = $attachmentFactory->findById($attachmentId);

            if (!$attachment) {
                throw new pocketlistsNotFoundException();
            }

            $attachmentFactory->delete($attachment);

            $this->logService->add(
                $this->logService->getFactory()->createNewAttachmentLog(
                    (new pocketlistsLogContext())
                        ->setItem($item)
                        ->setAttachment($attachment),
                    pocketlistsLog::ACTION_DELETE
                )
            );
        } else {
            $this->setError('no attachments');
        }
    }
}
