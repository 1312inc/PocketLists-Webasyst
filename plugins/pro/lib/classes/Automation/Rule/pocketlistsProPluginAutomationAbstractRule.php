<?php

/**
 * Class pocketlistsProPluginAbstractAutomationRule
 */
abstract class pocketlistsProPluginAutomationAbstractRule implements pocketlistsProPluginAutomationRuleInterface
{
    /**
     * @var pocketlistsProPluginAutomationRuleInterface
     */
    protected $nextRule;

    /**
     * @param pocketlistsProPluginAutomationRuleInterface $rule
     *
     * @return pocketlistsProPluginAutomationRuleInterface
     */
    public function setNext(pocketlistsProPluginAutomationRuleInterface $rule)
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
