<?php

/**
 * Interface pocketlistsHydratorInterface
 */
interface pocketlistsHydratorInterface
{
    /**
     * @param pocketlistsHydratableInterface $object
     * @param array                          $fields
     *
     * @return mixed
     */
    public function extract(pocketlistsHydratableInterface $object, array $fields = []);

    /**
     * @param pocketlistsHydratableInterface $object
     * @param array                          $data
     *
     * @return mixed
     */
    public function hydrate(pocketlistsHydratableInterface $object, array $data);
}
