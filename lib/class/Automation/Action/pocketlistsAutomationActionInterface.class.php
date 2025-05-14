<?php

/**
 * Interface kmAutomationActionInterface
 */
interface pocketlistsAutomationActionInterface extends pocketlistsSerializableInterface, pocketlistsHtmlEditableInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @param pocketlistsAutomation $automation
     * @param $params
     *
     * @return mixed
     */
    public function execute(pocketlistsAutomation $automation, $params);

    /**
     * @param pocketlistsAutomation $automation
     * @param $params
     *
     * @return mixed
     */
    public function delay(pocketlistsAutomation $automation, $params);
}
