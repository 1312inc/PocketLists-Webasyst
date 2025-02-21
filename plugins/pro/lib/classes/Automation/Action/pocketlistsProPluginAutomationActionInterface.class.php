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
     * @param pocketlistsProPluginAutomation $automation
     * @param                                $params
     *
     * @return mixed
     */
    public function execute(pocketlistsProPluginAutomation $automation, $params);

    /**
     * @param pocketlistsProPluginAutomation $automation
     * @param                                $params
     *
     * @return mixed
     */
    public function delay(pocketlistsProPluginAutomation $automation, $params);
}
