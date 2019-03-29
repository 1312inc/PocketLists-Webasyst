<?php

/**
 * Interface pocketlistsEntityInterface
 */
interface pocketlistsHydratableInterface
{
    public function afterHydrate();
    public function beforeExtract();
}
