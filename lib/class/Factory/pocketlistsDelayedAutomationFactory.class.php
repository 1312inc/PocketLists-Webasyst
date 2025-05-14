<?php

/**
 * Class pocketlistsDelayedAutomationFactory
 *
 * @method pocketlistsDelayedAutomationModel getModel()
 */
class pocketlistsDelayedAutomationFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsDelayedAutomation';

    /**
     * @param string $time
     *
     * @return pocketlistsDelayedAutomation[]
     * @throws waException
     */
    public function findByNewByTime($time)
    {
        $data = $this->getModel()->getNewByDelayedTime($time);

        return $this->generateWithData($data, true);
    }

    /**
     * @return pocketlistsDelayedAutomation
     */
    public function createNew()
    {
        /** @var pocketlistsDelayedAutomation $entity */
        $entity = parent::createNew();

        $entity
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setStatus(pocketlistsDelayedAutomation::STATUS_NEW);

        return $entity;
    }
}
