<?php

/**
 * Class pocketlistsPocketFactory
 *
 * @method pocketlistsPocketModel getModel()
 */
class pocketlistsPocketFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsPocket';

    /**
     * @param pocketlistsUser|null $user
     *
     * @return pocketlistsPocket[]
     * @throws waException
     */
    public function getAllPockets(pocketlistsUser $user = null)
    {
        $contactId = $user instanceof pocketlistsUser ? $user->getContact()->getId() : false;
        $data = $this->getModel()->getAllPockets($contactId);

        return $this->generateWithData($data, true);
    }
}
