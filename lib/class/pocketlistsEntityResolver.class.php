<?php

/**
 * Class pocketlistsEntityResolver
 */
class pocketlistsEntityResolver
{
    /**
     * @param string $class
     * @param int    $id
     *
     * @return pocketlistsEntity
     * @throws pocketlistsAssertException
     * @throws waException
     */
    public function getEntityById($class, $id)
    {
        $entity = pl2()->getEntityFactory($class)->findById($id);

        pocketlistsAssert::instance($entity, $class);

        return $entity;
    }
}
