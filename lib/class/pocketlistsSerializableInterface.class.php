<?php

/**
 * Interface pocketlistsSerializableInterface
 */
interface pocketlistsSerializableInterface extends JsonSerializable
{
    /**
     * @param array $json
     *
     * @return pocketlistsSerializableInterface
     */
    public function load(array $json);
}
