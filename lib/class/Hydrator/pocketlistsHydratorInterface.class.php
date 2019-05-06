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
     * @return mixed
     */
    public function extract(pocketlistsHydratableInterface $object, array $fields = [], $dbFields = []);

    /**
     * @param pocketlistsHydratableInterface $object
     * @param array                          $data
     *
     * @return mixed
     */
    public function hydrate(pocketlistsHydratableInterface $object, array $data);
}
