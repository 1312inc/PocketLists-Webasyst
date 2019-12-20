<?php

/**
 * Class pocketlistsItemLinkFactory
 *
 * @method pocketlistsItemLinkModel getModel()
 */
class pocketlistsItemLinkFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsItemLink';

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsItemLink[]
     * @throws waException
     */
    public function findForItem(pocketlistsItem $item)
    {
        $data = $this->getModel()->getByItemId($item->getId());

        return $this->generateWithData($data, true);
    }

    /**
     * @param string $app
     * @param string $entityType
     * @param int    $entityId
     *
     * @return pocketlistsItemLink[]
     * @throws waException
     */
    public function findWithAppEntityTypeAndId($app, $entityType, $entityId)
    {
        $data = $this->getModel()->getByField(
            [
                'app'         => $app,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
            ],
            true
        );

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsItem $item
     * @param array           $linkData
     * @param bool            $checkAccess
     *
     * @return bool|pocketlistsItemLink
     * @throws waException
     */
    public function createFromDataForItem(pocketlistsItem $item, array $linkData, $checkAccess = true)
    {
        /** @var pocketlistsAppLinkInterface $app */
        $app = pl2()->getLinkedApp($linkData['app']);

        if ($checkAccess && !$app->userCanAccess()) {
            return false;
        }

        foreach ($linkData as $key => $value) {
            if ($value === '') {
                unset($linkData[$key]);
            }
        }

        /** @var pocketlistsItemLink $itemLink */
        $itemLink = pl2()->getHydrator()->hydrate($this->createNew(), $linkData);
        $itemLink->setItem($item);

        try {
            $this->save($itemLink);
            $item->addAppLinks($itemLink);

            return $itemLink;
        } catch (waException $ex) {
        }

        return false;
    }
}
