<?php

/**
 * Interface kmUnserializableInterface
 */
interface kmSerializableInterface extends JsonSerializable
{
    /**
     * @param array $json
     */
    public function load(array $json);
}
