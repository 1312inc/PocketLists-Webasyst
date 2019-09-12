<?php

/**
 * Class pocketlistsProPluginWaEventListener
 */
class pocketlistsProPluginWaEventListener
{
    /**
     * @param pocketlistsEvent $event
     *
     * @throws waException
     */
    public function onEntityInsertBefore(pocketlistsEvent $event)
    {
        $object = $event->getObject();

        switch (get_class($object)) {
            case pocketlistsItem::class:
                (new pocketlistsProPluginItemEventListener())->onInsert($event);
        }
    }

    /**
     * @param pocketlistsEvent $event
     *
     * @throws waException
     */
    public function onEntityUpdateBefore(pocketlistsEvent $event)
    {
        $object = $event->getObject();

        switch (get_class($object)) {
            case pocketlistsItem::class:
                (new pocketlistsProPluginItemEventListener())->onUpdate($event);
        }
    }
}
