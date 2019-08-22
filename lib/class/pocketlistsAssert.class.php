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

    /**
     * @param $variable
     * @param $value
     *
     * @throws pocketlistsAssertException
     */
    public static function gt($variable, $value)
    {
        if ($variable <= $value) {
            throw new pocketlistsAssertException(sprintf('Variable %s should be greater then %s', $variable, $value));
        }
    }

    /**
     * @param $variable
     * @param $value
     *
     * @throws pocketlistsAssertException
     */
    public static function gte($variable, $value)
    {
        if ($variable < $value) {
            throw new pocketlistsAssertException(sprintf('Variable %s should be greater or equal then %s', $variable, $value));
        }
    }
}
