<?php

/**
 * Class pocketlistsProPluginAutomationRuleShopShipping
 */
class pocketlistsProPluginAutomationRuleShopShipping extends pocketlistsProPluginAutomationRuleShopPayment
{
    const IDENTIFIER = 'shipping';

    /**
     * @return string
     */
    public function getLabel()
    {
        return _wp('Shipping');
    }

    /**
     * @return array
     * @throws waException
     */
    public function getPossibleValues()
    {
        if ($this->possibleValues === null) {
            $model = new shopPluginModel();
            $instances = $model->listPlugins(shopPluginModel::TYPE_SHIPPING, ['all' => true,]);
//        foreach ($instances as &$instance) {
//            $instance['installed'] = isset($plugins[$instance['plugin']]);
//
//            unset($instance);
//        }

            foreach ($instances as $instance) {
                $this->possibleValues[$instance['plugin'].$instance['id']] = $instance['name'];
            }
        }

        return $this->possibleValues;
    }
}
