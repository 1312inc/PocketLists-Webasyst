<?php

/**
 * Class pocketlistsProPluginLabelFactory
 *
 * @method pocketlistsProPluginLabelModel getModel()
 */
class pocketlistsProPluginLabelFactory extends pocketlistsFactory
{
    protected $entity;

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsProPluginLabel|null
     * @throws pocketlistsLogicException
     * @throws waException
     */
    public function findForItem(pocketlistsItem $item)
    {
        $labelId = $item->getDataField('pro_label_id');
        /** @var pocketlistsProPluginLabel $label */
        $label = $this->findById($labelId);

        return $label ?: null;
    }

    /**
     * @param pocketlistsProPluginLabel $entity
     *
     * @return bool
     * @throws waException
     */
    public function delete(pocketlistsEntity $entity)
    {
        if (parent::delete($entity)) {
            pl2()->getModel(pocketlistsItem::class)->updateByField('pro_label', $entity->getId(), ['pro_label' => null]);

            return  true;
        }

        return false;
    }
}
