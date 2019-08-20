<?php

/**
 * Interface kmAbstractAutomationRule
 */
abstract class kmAbstractAutomationRule implements kmAutomationRuleInterface
{
    /**
     * @var kmAutomationRuleInterface
     */
    protected $nextRule;

    /**
     * @param kmAutomationRuleInterface $rule
     *
     * @return kmAutomationRuleInterface
     */
    public function setNext(kmAutomationRuleInterface $rule)
    {
        $this->nextRule = $rule;

        return  $rule;
    }

    /**
     * @return bool
     */
    public function match()
    {
        if ($this->nextRule) {
            return $this->nextRule->match();
        }

        return false;
    }
}
