<?php

/**
 * Class pocketlistsProPluginHookHandlerListAccesses
 */
class pocketlistsProPluginHookHandlerListAccesses extends pocketlistsProPluginAbstractHookHandler
{
    /**
     * @param pocketlistsEvent|null $event
     *
     * @return string
     * @throws pocketlistsAssertException
     */
    public function handle($event = null)
    {
        pocketlistsAssert::instance($event, pocketlistsEvent::class);
        $list = $event->getObject();
        pocketlistsAssert::instance($list, pocketlistsList::class);

        $action = new pocketlistsProPluginActivityListAction(['entity_id' => $list->getId()]);

        return $action->display(false);
    }
}
