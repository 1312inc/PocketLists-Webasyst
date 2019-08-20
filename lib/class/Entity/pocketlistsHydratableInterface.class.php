<?php

/**
 * Interface pocketlistsEntityInterface
 */
interface pocketlistsHydratableInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function afterHydrate($data = []);

    /**
     * @param array $fields
     *
     * @return array
     */
    public function beforeExtract(array &$fields);

    /**
     * @param array $fields
     *
     * @return array
     */
    public function afterExtract(array &$fields);
}
