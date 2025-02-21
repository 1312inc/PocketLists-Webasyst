<?php

/**
 * Class pocketlistsProPluginAutomationShopDialogAction
 */
class pocketlistsProPluginAutomationCrudActions extends pocketlistsProPluginAbstractEntityCrudActions
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsLogicException
     * @throws waException
     */
    public function preExecute()
    {
        parent::preExecute();

        if (!pl2()->getLinkedApp('shop')->isEnabled()) {
            throw new pocketlistsLogicException(_wp('Shop-Script app is not installed'));
        }
    }

    /**
     * @return pocketlistsFactory
     * @throws waException
     */
    protected function getFactory()
    {
        return pl2()->getEntityFactory(pocketlistsProPluginAutomation::class);
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return 'Automation';
    }
}
