<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopShippment
 */
class pocketlistsProPluginAutomationRuleShopShipping extends pocketlistsProPluginAutomationRuleShopPayment
{
    const IDENTIFIER = 'shipping';

    /**
     * @return string
     */
    public function getLabel()
    {
        return _wp('Shippping');
    }

    /**
     * @return array
     * @throws waException
     */
    public function getPossibleValues()
    {
        if ($this->possibleValues === null) {
            $plugins = shopShipping::getList();

            $model = new shopPluginModel();
            $instances = $model->listPlugins(shopPluginModel::TYPE_SHIPPING, ['all' => true,]);
//        foreach ($instances as &$instance) {
//            $instance['installed'] = isset($plugins[$instance['plugin']]);
//
//            unset($instance);
//        }
            $this->possibleValues = array_combine(
                array_column($instances, 'plugin'),
                array_column($instances, 'name')
            );
        }

        return $this->possibleValues;
    }
}
