<?php

/**
 * Interface pocketlistsEntityInterface
 */
interface pocketlistsHydratableInterface
{
    /**
     * @return mixed
     */
    public function afterHydrate();

    /**
     * @param array $fields
     *
     * @return array
     */
    public function beforeExtract(array &$fields);
}
