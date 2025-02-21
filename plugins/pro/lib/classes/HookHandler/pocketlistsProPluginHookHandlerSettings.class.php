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
        $this->getView()->assign('isAdmin', pocketlistsRBAC::isAdmin());
        $sidebarLi = $this->getView()->fetch($this->getViewTemplate('backend_settings.sidebar_li'));

        return [
            'sidebar_li' => $sidebarLi,
        ];
    }
}
