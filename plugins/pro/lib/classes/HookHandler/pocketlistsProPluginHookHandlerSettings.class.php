<?php

/**
 * Class pocketlistsProPluginHookHandlerSettings
 */
class pocketlistsProPluginHookHandlerSettings extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param null|mixed $params
     *
     * @return mixed
     */
    public function handle($params = null)
    {
        $sidebarLi = $this->getView()->fetch($this->getViewTemplate('backend_settings.sidebar_li'));

        return [
            'sidebar_li' => $sidebarLi,
        ];
    }
}
