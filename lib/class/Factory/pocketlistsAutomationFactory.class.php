<?php

/**
 * Class pocketlistsAutomationFactory
 *
 * @method pocketlistsAutomationModel getModel()
 */
class pocketlistsAutomationFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsAutomation';

    /**
     * @param string $event
     * @param string $type
     *
     * @return pocketlistsAutomation[]
     * @throws waException
     */
    public function findByEventAndType($event, $type)
    {
        $data = $this->getModel()->getByEventNameAndType($event, $type);

        return $this->generateWithData($data, true);
    }

    /**
     * @return pocketlistsAutomation
     */
    public function createNew()
    {
        /** @var pocketlistsAutomation $entity */
        $entity = parent::createNew();

        $entity
            ->setCreatedBy(pl2()->getUser()->getId())
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setEvent(pocketlistsAutomationShopOrderActionEvent::NAME);

        return $entity;
    }
}
