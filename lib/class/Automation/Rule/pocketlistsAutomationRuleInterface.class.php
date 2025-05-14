<?php

/**
 * Interface pocketlistsAutomationRuleInterface
 */
interface pocketlistsAutomationRuleInterface
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
     * @return pocketlistsAutomationRuleInterface
     */
    public function skipDelayed($skipDelayed);
}
