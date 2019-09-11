<?php

/**
 * Class pocketlistsProPluginHookHandlerHead
 */
class pocketlistsProPluginHookHandlerHead extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param null|pocketlistsEvent $event
     *
     * @return mixed
     */
    public function handle($event = null)
    {
        return $this->getView()->fetch($this->getViewTemplate('backend_head'));
    }
}
