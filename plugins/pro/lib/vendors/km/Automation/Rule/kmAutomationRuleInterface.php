<?php

/**
 * Interface kmAutomationRuleInterface
 */
interface kmAutomationRuleInterface extends JsonSerializable, kmUnserializableInterface, kmHtmlEditableInterface
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
}
