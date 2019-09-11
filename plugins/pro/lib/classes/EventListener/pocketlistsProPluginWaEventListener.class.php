<?php

/**
 * Class pocketlistsProPluginWaEventListener
 */
class pocketlistsProPluginWaEventListener
{
    /**
     * @param array $eventData
     *
     * @return mixed
     */
    public function onEntityInsertBefore(array $eventData)
    {
        $object = $eventData['entity'];
        $eventName = '';

        switch (get_class($object)) {
            case pocketlistsItem::class:
                $eventName = pocketlistsEventStorage::ITEM_INSERT;
        }

        $event = new pocketlistsEvent($eventName, $object, $eventData['data']);

        return pl2()->getEventDispatcher()->dispatch($event);
    }

    /**
     * @param array $eventData
     *
     * @return mixed
     */
    public function onEntityUpdateBefore(array $eventData)
    {
        $object = $eventData['entity'];
        $eventName = '';

        switch (get_class($object)) {
            case pocketlistsItem::class:
                $eventName = pocketlistsEventStorage::ITEM_UPDATE;
        }

        $event = new pocketlistsEvent($eventName, $object, $eventData['data']);

        return pl2()->getEventDispatcher()->dispatch($event);
    }
}
