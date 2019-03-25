<?php

/**
 * Interface pocketlistsHydratorInterface
 */
interface pocketlistsHydratorInterface
{
    /**
     * @param       $object
     * @param array $fields
     *
     * @return array
     */
    public function extract($object, array $fields = []);

    /**
     * @param object $object
     * @param array  $data
     *
     * @return object
     */
    public function hydrate($object, array $data);
}