<?php

/**
 * Defines a dispatcher for events.
 */
interface pocketlistsEventDispatcherInterface
{
    /**
     * Provide all relevant listeners with an event to process.
     *
     * @param pocketlistsEventInterface $event
     *
     * @return pocketlistsListenerResponseInterface
     */
    public function dispatch(pocketlistsEventInterface $event);
}
