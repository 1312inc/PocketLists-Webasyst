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
     * @param pocketlistsContact|null $user
     *
     * @return pocketlistsPocket[]
     * @throws waException
     */
    public function findAllForUser(pocketlistsContact $user = null)
    {
        $contactId = $user instanceof pocketlistsContact ? $user->getId() : pl2()->getUser()->getId();
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

    /**
     * @param pocketlistsList $list
     *
     * @return pocketlistsPocket
     * @throws waException
     */
    public function findByList(pocketlistsList $list)
    {
        return $this->findByListId($list->getId());
    }

    /**
     * @param int $listId
     *
     * @return pocketlistsPocket
     * @throws waException
     */
    public function findByListId($listId)
    {
        $data = $this->getModel()->getByListId($listId);

        return $this->generateWithData($data);
    }
}
