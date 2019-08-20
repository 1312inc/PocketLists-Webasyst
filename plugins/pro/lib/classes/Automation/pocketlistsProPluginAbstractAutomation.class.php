<?php

/**
 * Class pocketlistsProPluginAbstractAutomation
 */
abstract class pocketlistsProPluginAbstractAutomation implements kmAutomationEventInterface
{
    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @return kmAutomationInterface[]
     * @throws waException
     */
    public function getAutomations()
    {
        /** @var pocketlistsProPluginAutomationFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsProPluginAutomation::class);

        return $factory->findByEventAndType($this->getName(), $this->getType());
    }
}
