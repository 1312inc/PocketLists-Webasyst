<?php

/**
 * Interface kmAutomationActionInterface
 */
interface pocketlistsProPluginAutomationActionInterface extends pocketlistsProPluginSerializableInterface, pocketlistsProPluginHtmlEditableInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return mixed
     */
    public function perform();
}
