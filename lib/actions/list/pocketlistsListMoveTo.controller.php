<?php

/**
 * Class pocketlistsListMoveToController
 */
class pocketlistsListMoveToController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsNotFoundException
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $pocket_id = waRequest::post('pocket_id', 0, waRequest::TYPE_INT);
        if (!$pocket_id) {
            throw new pocketlistsNotFoundException(_w('No pocket/id and list/id params specified'));
        }

        $list = $this->getList();

        if ($list->getPocket()->getId() == $pocket_id) {
            $this->setError(_w('The list is in the pocket already'));

            return;
        }

        /** @var pocketlistsPocket $pocket */
        $pocket = pl2()->getEntityFactory(pocketlistsPocket::class)->findById($pocket_id);

        if (!$pocket) {
            throw new pocketlistsNotFoundException(_w('Pocket and list not found'));
        }

        if (pocketlistsRBAC::contactHasAccessToPocket($pocket) != pocketlistsRBAC::RIGHT_ADMIN) {
            throw new pocketlistsForbiddenException();
        }

        if (!pocketlistsRBAC::canAccessToList($list)) {
            throw new pocketlistsForbiddenException();
        }

        $list->setPocket($pocket);
        if (!pl2()->getEntityFactory(pocketlistsList::class)->update($list, ['pocket_id'])) {
            $this->setError(_w('List move error'));
        } else {
            $this->logService->add(
                $this->logService->getFactory()->createNewListLog(
                    (new pocketlistsLogContext())
                        ->setList($list)
                        ->setAdditional($pocket->getId())
                        ->addParam(
                            [
                                'pocket' => ['name' => $pocket->getName()],
                            ]
                        ),
                    pocketlistsLog::ACTION_UPDATE
                )
            );
        }
    }
}
