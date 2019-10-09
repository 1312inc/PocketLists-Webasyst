<?php

/**
 * Class pocketlistsProPluginAutomationFactory
 *
 * @method pocketlistsProPluginAutomationModel getModel()
 */
class pocketlistsProPluginAutomationFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsProPluginAutomation';

    /**
     * @param string $event
     * @param string $type
     *
     * @return pocketlistsProPluginAutomation[]
     * @throws waException
     */
    public function findByEventAndType($event, $type)
    {
        $data = $this->getModel()->getByEventNameAndType($event, $type);

        return $this->generateWithData($data, true);
    }

    /**
     * @return pocketlistsProPluginAutomation
     */
    public function createNew()
    {
        /** @var pocketlistsProPluginAutomation $entity */
        $entity = parent::createNew();

        $entity
            ->setCreatedBy(pl2()->getUser()->getId())
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setEvent(pocketlistsProPluginAutomationShopOrderActionEvent::NAME);

        return $entity;
    }
}
