<?php

/**
 * Class pocketlistsItemAssignToController
 */
class pocketlistsItemAssignToController extends pocketlistsJsonController
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $team_id = waRequest::post('team_id', 0, waRequest::TYPE_INT);

        if ($team_id > 0) {
            /** @var pocketlistsItemFactory $itemFactory */
            $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
            /** @var pocketlistsItem $item */
            $item = $this->getItem();

            $contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($team_id);

            if (!$contact->isExists()) {
                throw new waException(_w('Contact not found'), 404);
            }

            if ($item
                && $item->getListId()
                && pocketlistsRBAC::canAccessToList($item->getList())
                && pocketlistsRBAC::canAccessToList($item->getList(), $contact->getId())
            ) {
                // todo: childs??
                $item
                    ->setAssignedContactId($contact->getId())
                    ->setUpdateDatetime(date('Y-m-d H:i:s'));
                $saved = $itemFactory->save($item);

                if ($saved) {
                    (new pocketlistsNotificationAboutNewAssign())->notify($item);

                    $this->logService->add(
                        $this->logService->getFactory()->createNewAfterItemAssign(
                            (new pocketlistsLogContext())
                                ->setItem($item)
                                ->setAdditional($contact)
                        )
                    );

                    $this->response = $contact->getName();
                } else {
                    $this->errors = 'db error';
                }
            } else {
                $this->errors = 'no such item or list or contact or access error';
            }
        } else {
            $this->errors = 'no id';
        }
    }
}
