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
     * @return bool
     */
    public function send();

    /**
     * @return string
     */
    public function getError();
}
