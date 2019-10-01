<?php

/**
 * Interface pocketlistsProPluginSerializableInterface
 */
interface pocketlistsProPluginSerializableInterface extends JsonSerializable
{
    /**
     * @param array $json
     */
    public function load(array $json);
}
