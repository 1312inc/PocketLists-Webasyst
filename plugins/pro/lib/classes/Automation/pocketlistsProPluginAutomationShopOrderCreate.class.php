<?php

/**
 * Class pocketlistsProPluginAutomationShopOrderCreate
 */
class pocketlistsProPluginAutomationShopOrderCreate extends pocketlistsProPluginAbstractAutomation
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'order_create';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'shop';
    }
}
