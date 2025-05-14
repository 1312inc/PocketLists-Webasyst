<?php

/**
 * Class pocketlistsProPluginHookHandlerSettings
 */
class pocketlistsProPluginHookHandlerSettings extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param null|pocketlistsEvent $event
     *
     * @return array|mixed
     * @throws waException
     */
    public function handle($event = null)
    {
        if (wa()->whichUI(pocketlistsHelper::APP_ID) !== '1.3') {
            return [];
        }
        $this->getView()->assign('isAdmin', pocketlistsRBAC::isAdmin());
        $sidebarLi = $this->getView()->fetch($this->getViewTemplate('backend_settings.sidebar_li'));

        return [
            'sidebar_li' => $sidebarLi,
        ];
    }
}
