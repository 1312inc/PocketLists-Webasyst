<?php

/**
 * Interface pocketlistsProPluginAutomationInterface
 */
interface pocketlistsProPluginAutomationInterface
{
    /**
     * @return bool
     */
    public function checkRules();

    public function performAction();
}
