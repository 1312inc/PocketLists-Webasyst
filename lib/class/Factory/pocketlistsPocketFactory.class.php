<?php

/**
 * Class pocketlistsPocketFactory
 *
 * @method pocketlistsPocketModel getModel()
 */
class pocketlistsPocketFactory extends pocketlistsFactory
{
    protected $entity;

    /**
     * @param pocketlistsContact|null $user
     *
     * @return pocketlistsPocket[]
     * @throws waException
     */
    public function getAllPocketsForUser(pocketlistsContact $user = null)
    {
        $contactId = $user instanceof pocketlistsContact ? $user->getId() : false;
        $data = $this->getModel()->getAllPockets($contactId);

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsPocket $pocket
     *
     * @return bool
     * @throws waException
     */
    public function delete(pocketlistsEntity $pocket)
    {
        // delete from wa_contact_rights
        $wcr = new waContactRightsModel();
        $wcr->deleteByField('name', 'pocket.'.$pocket->getId());

        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);

        $lists = $pocket->getLists();
        foreach ($lists as $list) {
            $listFactory->delete($list);
        }

        return parent::delete($pocket);
    }
}
