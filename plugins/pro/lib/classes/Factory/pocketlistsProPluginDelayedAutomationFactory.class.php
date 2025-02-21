<?php

/**
 * Class pocketlistsProPluginDelayedAutomationFactory
 *
 * @method pocketlistsProPluginDelayedAutomationModel getModel()
 */
class pocketlistsProPluginDelayedAutomationFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsProPluginDelayedAutomation';

    /**
     * @param string $time
     *
     * @return pocketlistsProPluginDelayedAutomation[]
     * @throws waException
     */
    public function findByNewByTime($time)
    {
        $data = $this->getModel()->getNewByDelayedTime($time);

        return $this->generateWithData($data, true);
    }

    /**
     * @return pocketlistsProPluginDelayedAutomation
     */
    public function createNew()
    {
        /** @var pocketlistsProPluginDelayedAutomation $entity */
        $entity = parent::createNew();

        $entity
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setStatus(pocketlistsProPluginDelayedAutomation::STATUS_NEW);

        return $entity;
    }
}
