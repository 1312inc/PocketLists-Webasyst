<?php

/**
 * Class pocketlistsEntity
 */
abstract class pocketlistsEntity implements pocketlistsHydratableInterface
{
    /**
     * @return array
     */
    public function getDbFields()
    {
        return $this->getModel()->getMetadata();
    }

    /**
     * @param array $data
     *
     * @return mixed|void
     */
    public function afterHydrate($data = [])
    {
    }

    /**
     * @param array $fields
     *
     * @return array|void
     */
    public function beforeExtract(array &$fields)
    {
    }
}
