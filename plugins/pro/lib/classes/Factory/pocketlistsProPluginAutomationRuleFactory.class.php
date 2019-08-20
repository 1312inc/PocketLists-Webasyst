<?php

/**
 * Class pocketlistsProPluginAutomationRuleFactory
 */
class pocketlistsProPluginAutomationRuleFactory
{
    /**
     * @param array  $data
     * @param string $type
     *
     * @return kmAutomationRuleInterface
     * @throws pocketlistsLogicException
     */
    public static function create(array $data, $type)
    {
        switch ($type) {
            case pocketlistsProPluginAutomation::TYPE_SHOP:
                $rule = new pocketlistsProPluginAutomationShopOrderRule();
                $rule->load($data);

                return $rule;
        }

        throw new pocketlistsLogicException(sprintf('Unknown rule type %s', $type));
    }
}
