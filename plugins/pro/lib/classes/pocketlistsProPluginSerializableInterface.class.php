<?php

/**
 * Interface pocketlistsProPluginSerializableInterface
 */
interface pocketlistsProPluginSerializableInterface extends JsonSerializable
{
    /**
     * @param array $json
     *
     * @return pocketlistsProPluginSerializableInterface
     */
    public function load(array $json);
}
