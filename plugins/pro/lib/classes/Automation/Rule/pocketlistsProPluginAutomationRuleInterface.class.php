<?php

/**
 * Interface pocketlistsProPluginAutomationRuleInterface
 */
interface pocketlistsProPluginAutomationRuleInterface
{
    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return mixed
     */
    public function getPossibleValues();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param $data
     *
     * @return bool
     */
    public function match($data);
}
