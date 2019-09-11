<?php

/**
 * Interface pocketlistsHookHandlerInterface
 */
interface pocketlistsHookHandlerInterface
{
    /**
     * @param pocketlistsEvent|null|array $event
     *
     * @return mixed
     */
    public function handle($event = null);
}
