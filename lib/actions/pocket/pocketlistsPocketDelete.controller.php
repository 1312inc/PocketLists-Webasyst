<?php

/**
 * Class pocketlistsPocketDeleteController
 */
class pocketlistsPocketDeleteController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsNotFoundException
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $pocket = $this->getPocket();

        $available_pockets = pocketlistsRBAC::getAccessPocketForContact();
        if (!in_array($pocket->getId(), $available_pockets)) {
            throw new pocketlistsForbiddenException();
        }

        $this->response = (int)pl2()->getEntityFactory(pocketlistsPocket::class)->delete($pocket);
    }
}
