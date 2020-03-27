<?php

/**
 * Class pocketlistsProPluginAutomationRuleShop
 */
abstract class pocketlistsProPluginAutomationRuleShop extends pocketlistsProPluginAutomationRuleAbstract
{
    /**
     * pocketlistsProPluginAutomationRuleShop constructor.
     *
     * @throws pocketlistsLogicException
     */
    public function __construct()
    {
        if (!wa()->appExists('shop')) {
            throw new pocketlistsLogicException('No shop');
        }
        wa('shop');
    }
}
