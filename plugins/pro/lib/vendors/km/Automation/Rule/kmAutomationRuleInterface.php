<?php

/**
 * Interface kmAutomationRuleInterface
 */
interface kmAutomationRuleInterface extends kmSerializableInterface, kmHtmlEditableInterface
{
    /**
     * @param kmAutomationRuleInterface $rule
     *
     * @return kmAutomationRuleInterface
     */
    public function setNext(kmAutomationRuleInterface $rule);

    /**
     * @return bool
     */
    public function match();

    /**
     * @return string
     */
    public function getGroup();
}
