<?php

/**
 * Class pocketlistsAutomationRuleShop
 */
abstract class pocketlistsAutomationRuleShop extends pocketlistsAutomationRuleAbstract
{
    /**
     * pocketlistsAutomationRuleShop constructor.
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
