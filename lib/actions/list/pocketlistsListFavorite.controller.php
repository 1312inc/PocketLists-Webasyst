<?php

/**
 * Class pocketlistsListFavoriteController
 */
class pocketlistsListFavoriteController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $list = $this->getList();

        $status = waRequest::post('status', false);

        if ($status) {
            $list->getKeyItem()->makeFavorite($this->user);
        } else {
            $list->getKeyItem()->removeFavorite($this->user);
        }

        $this->logService->add(
            $this->logService->getFactory()->createNewListLog(
                (new pocketlistsLogContext())
                    ->setList($list)
                    ->addParam(
                        [
                            'list' => ['favorite' => $status],
                        ]
                    ),
                pocketlistsLog::ACTION_UPDATE
            )
        );
    }
}
