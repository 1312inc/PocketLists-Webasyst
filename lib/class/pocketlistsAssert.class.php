<?php

/**
 * Class pocketlistsAssert
 */
class pocketlistsAssert
{
    /**
     * @param $object
     * @param $class
     *
     * @throws pocketlistsAssertException
     */
    public static function instance($object, $class)
    {
        $type = gettype($object);
        if ($type !== 'object') {
            throw new pocketlistsAssertException(sprintf('Expected %s. Got %s', $class, $type));
        }

        if (!$object instanceof $class) {
            throw new pocketlistsAssertException(sprintf('Expected %s. Got %s', $class, get_class($object)));
        }
    }
}
