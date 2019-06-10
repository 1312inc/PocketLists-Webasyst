<?php

/**
 * Class pocketlistsListUpdateController
 */
class pocketlistsListUpdateController extends pocketlistsJsonController
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);

        if (empty($data['pocket_id'])) {
            $this->setError('no pocket id');

            return;
        }

        $pocket = $this->getPocket($data['pocket_id']);
        if (pocketlistsRBAC::contactHasAccessToPocket($pocket) != pocketlistsRBAC::RIGHT_ADMIN) {
            throw new pocketlistsForbiddenException();
        }

        /** @var pocketlistsItemFactory $listFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);

        $newList = true;
        try {
            $list = $this->getList();
            $newList = false;
        } catch (pocketlistsNotFoundException $ex) {
            /** @var pocketlistsList $list */
            $list = $listFactory->createNew();
            $list
                ->setPocket($pocket)
                ->setColor($pocket->getColor())
                ->setContact($this->user)
                ->setCreateDatetime(date("Y-m-d H:i:s"))
                ->setKeyItem($itemFactory->generateWithData($data));
        }

        /** @var pocketlistsList $list */
        pl2()->getHydrator()->hydrate($list, $data);
        pl2()->getHydrator()->hydrate($list->getKeyItem(), $data);

        $list_icons = (new pocketlistsListIcon())->getAll();
        $matched_icon = pocketlistsNaturalInput::matchCategory($list->getName());
        if ($matched_icon) {
            foreach ($list_icons as $listIconGroup => $icon) {
                if (isset($list_icons[$listIconGroup][$matched_icon])) {
                    $list->setIcon($list_icons[$listIconGroup][$matched_icon]);
                }
            }
        }

        if ($listFactory->save($list) && $newList) {
            // add access for user
            if (!pocketlistsRBAC::isAdmin()) {
                (new waContactRightsModel())->save(
                    $list->getContactId(),
                    pocketlistsHelper::APP_ID,
                    'list.'.$list->getId(),
                    pocketlistsRBAC::RIGHT_ACCESS
                );
            }

            // log this action
            $this->logAction(
                pocketlistsLogAction::LIST_CREATED,
                [
                    'list_id' => $list->getId(),
                ]
            );

            (new pocketlistsNotificationAboutNewList())->notifyAboutNewList($list);

            $this->logService->add(
                $this->logService->getFactory()->createNewListLog(
                    (new pocketlistsLogContext())->setList($list),
                    pocketlistsLog::ACTION_UPDATE
                )
            );
        }

        $this->response = ['id' => $list->getId()];
    }
}
