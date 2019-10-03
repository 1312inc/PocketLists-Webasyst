<?php

/**
 * Class pocketlistsProPluginSettingsShopscriptDialogAction
 */
class pocketlistsProPluginSettingsShopscriptDialogAction extends pocketlistsViewAction
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
            throw new pocketlistsLogicException(_wp('No Shop-Script installed'));
        }
    }

    /**
     * @param null|array $params
     *
     * @return mixed
     */
    public function runAction($params = null)
    {
        $actionId = waRequest::get('action', '', waRequest::TYPE_STRING_TRIM);

        pocketlistsProPlugin::getInstance()->getAutomationService()->getAvailableEventsForGroup('shop');
    }
}
