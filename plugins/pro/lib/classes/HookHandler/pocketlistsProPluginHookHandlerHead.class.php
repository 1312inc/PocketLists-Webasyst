<?php

/**
 * Class pocketlistsProPluginHookHandlerHead
 */
class pocketlistsProPluginHookHandlerHead extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param null|mixed $params
     *
     * @return mixed
     */
    public function handle($params = null)
    {
        return $this->getView()->fetch($this->getViewTemplate('backend_head'));
    }
}
