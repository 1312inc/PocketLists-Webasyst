<?php

/**
 * Class pocketlistsProPluginAutomationFactory
 *
 * @method pocketlistsProPluginAutomationModel getModel()
 */
class pocketlistsProPluginAutomationFactory extends pocketlistsFactory
{
    protected $entity;

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
}
