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

        return pl2()->event($eventName, $object, $eventData['data']);
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

        return pl2()->event($eventName, $object, $eventData['data']);
    }

    public function onOrderAction($params)
    {
        $automator = new kmAutomation();

        $automator->run(new pocketlistsProPluginAutomationShopOrderCreate());
    }
}
