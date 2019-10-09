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
     * @param $params
     *
     * @return mixed
     */
    public function execute($params);
}
