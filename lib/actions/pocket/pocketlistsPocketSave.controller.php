<?php

/**
 * Class pocketlistsPocketSaveController
 */
class pocketlistsPocketSaveController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function execute()
    {
        $pocketData = waRequest::post('pocket', [], waRequest::TYPE_ARRAY);
        if (!$pocketData['id']) {
            unset($pocketData['id']);
        }

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);

        if (!empty($pocketData['id'])) {
            $pocket = $this->getPocket($pocketData['id']);

            unset($pocketData['id']);

            pl2()->getHydrator()->hydrate($pocket, $pocketData);

            if (!$pocketFactory->save($pocket)) {
                $this->setError('some error on save pocket');

                return;
            }

            $this->logService->add(
                $this->logService->getFactory()->createNewPocketLog(
                    (new pocketlistsLogContext())->setPocket($pocket),
                    pocketlistsLog::ACTION_UPDATE
                )
            );
        } else {
            /** @var pocketlistsPocket $pocket */
            $pocket = $pocketFactory->createNew();
            pl2()->getHydrator()->hydrate($pocket, $pocketData);

            if ($pocketFactory->save($pocket)) {
                // add full rights for this pocket
                (new waContactRightsModel())->save(
                    wa()->getUser()->getId(),
                    wa()->getApp(),
                    pocketlistsRBAC::POCKET_ITEM.'.'.$pocket->getId(),
                    pocketlistsRBAC::RIGHT_ADMIN
                );

                $this->logService->add(
                    $this->logService->getFactory()->createNewPocketLog(
                        (new pocketlistsLogContext())->setPocket($pocket),
                        pocketlistsLog::ACTION_ADD
                    )
                );
                // todo: update access rights for others
            } else {
                $this->setError('some error on save pocket');

                return;
            }
        }

        $this->response = pl2()->getHydrator()->extract($pocket);
    }
}

