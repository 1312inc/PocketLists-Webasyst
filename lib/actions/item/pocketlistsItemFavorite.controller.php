<?php

/**
 * Class pocketlistsItemFavoriteController
 */
class pocketlistsItemFavoriteController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsLogicException
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function execute()
    {
        $status = waRequest::post('status', false);

        $item = $this->getItem();

        if ($status) {
            $item->makeFavorite(pl2()->getUser());
        } else {
            $item->removeFavorite(pl2()->getUser());
        }

        $this->logService->add(
            $this->logService->getFactory()->createNewItemLog(
                (new pocketlistsLogContext())
                    ->setItem($item)
                    ->setParams(['item' => ['favorite' => $status]]),
                pocketlistsLog::ACTION_UPDATE
            )
        );
    }
}
