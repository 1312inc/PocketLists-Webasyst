<?php

/**
 * Interface pocketlistsProPluginAbstractAutomationEventInterface
 */
interface pocketlistsProPluginAbstractAutomationEventInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getType();

    public function applyAutomations();
}
