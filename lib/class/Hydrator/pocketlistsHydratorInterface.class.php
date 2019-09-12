<?php

/**
 * Interface pocketlistsHydratorInterface
 */
interface pocketlistsHydratorInterface
{
    /**
     * @param pocketlistsHydratableInterface $object
     * @param array                          $fields
     * @param array                          $dbFields
     *
     * @return array
     */
    public function extract(pocketlistsHydratableInterface $object, array $fields = [], $dbFields = []);

    /**
     * @param pocketlistsHydratableInterface $object
     * @param array                          $data
     *
     * @return object
     */
    public function hydrate(pocketlistsHydratableInterface $object, array $data);
}
