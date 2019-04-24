<?php

/**
 * Interface pocketlistsNotificationContentInterface
 */
interface pocketlistsNotificationContentInterface
{
    /**
     * @return string
     */
    public function toJson();

    /**
     * @param string $json
     */
    public function extractJson($json);

    /**
     * @return mixed
     */
    public function send();
}
