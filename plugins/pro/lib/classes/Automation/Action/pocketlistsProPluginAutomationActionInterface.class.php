<?php

/**
 * Interface kmAutomationActionInterface
 */
interface pocketlistsProPluginAutomationActionInterface extends pocketlistsProPluginSerializableInterface, pocketlistsProPluginHtmlEditableInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @return mixed
     */
    public function perform();
}
