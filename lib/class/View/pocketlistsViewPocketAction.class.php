<?php

/**
 * Class pocketlistsViewPocketAction
 */
class pocketlistsViewPocketAction extends pocketlistsViewAction
{
    /**
     * @param bool $id
     *
     * @return pocketlistsPocket
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    protected function getPocket($id = false)
    {
        $id = $id
            ?: waRequest::request('id', 0, waRequest::TYPE_INT)
                ?: waRequest::request('pocket_id', 0, waRequest::TYPE_INT);

        if (!$id) {
            throw new pocketlistsNotFoundException();
        }

        $item = pl2()->getEntityFactory(pocketlistsPocket::class)->findById($id);
        if (!$item) {
            throw new pocketlistsNotFoundException();
        }

        return $item;
    }
}
