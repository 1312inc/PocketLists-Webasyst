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
     * @param shopOrder $order
     *
     * @return bool
     * @throws pocketlistsLogicException
     */
    public function match($order)
    {
        if ($this->isEmpty()) {
            return true;
        }

        return pocketlistsProPluginComparision::compare($order->params['shipping_id'], $this->value, $this->compare);
    }

    /**
     * @return array
     * @throws waException
     */
    public function getPossibleValues()
    {
        if ($this->possibleValues === null) {
            $this->possibleValues = [];
            $instances = $this->getShopPluginModel()->listPlugins(shopPluginModel::TYPE_SHIPPING, ['all' => true]);

            foreach ($instances as $instance) {
                $this->possibleValues[$instance['id']] = $instance['name'];
            }
        }

        return $this->possibleValues;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function viewHtml()
    {
        if ($this->isEmpty()) {
            return '';
        }

        $instances = $this->getShopPluginModel()->listPlugins(shopPluginModel::TYPE_SHIPPING, array('all' => true));
        $name = ifset($instances, $this->value, 'name', sprintf_wp('No shipping method with the ID = %s is defined. Please check if settings need to be updated!', $this->value));

        return <<<HTML
<strong>{$this->getLabel()} {$this->compare} {$name}</strong>
HTML;
    }
}
