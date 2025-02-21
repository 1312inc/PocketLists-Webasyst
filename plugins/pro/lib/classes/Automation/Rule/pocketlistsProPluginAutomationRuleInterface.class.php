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

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * @param bool $skipDelayed
     *
     * @return pocketlistsProPluginAutomationRuleInterface
     */
    public function skipDelayed($skipDelayed);
}
