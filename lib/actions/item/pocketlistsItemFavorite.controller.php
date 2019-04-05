<?php

/**
 * Class pocketlistsItemFavoriteController
 */
class pocketlistsItemFavoriteController extends pocketlistsJsonController
{
    /**
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
    }
}
