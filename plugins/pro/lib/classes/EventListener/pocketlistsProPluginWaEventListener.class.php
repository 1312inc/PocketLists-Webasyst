<?php

/**
 * Class pocketlistsProPluginWaEventListener
 */
class pocketlistsProPluginWaEventListener
{
    /**
     * @param pocketlistsEvent $event
     *
     * @return array
     * @throws waException
     */
    public function onEntityInsertBefore(pocketlistsEvent $event)
    {
        if ($event->getObject() instanceof pocketlistsItem) {
            return (new pocketlistsProPluginItemEventListener())->onInsert($event);
        }

        return  [];
    }

    /**
     * @param pocketlistsEvent $event
     *
     * @return array
     * @throws waException
     */
    public function onEntityUpdateBefore(pocketlistsEvent $event)
    {
        if ($event->getObject() instanceof pocketlistsItem) {
                return (new pocketlistsProPluginItemEventListener())->onUpdate($event);
        }

        return  [];
    }

    public function onOrderAction($params)
    {
        $automator = new kmAutomation();

        $automator->run(new pocketlistsProPluginAutomationShopOrderCreate());
    }
}
