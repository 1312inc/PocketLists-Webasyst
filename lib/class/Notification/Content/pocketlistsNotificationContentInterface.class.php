<?php

/**
 * Interface pocketlistsNotificationContentInterface
 */
interface pocketlistsNotificationContentInterface
{
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

    /**
     * @return int
     */
    public function getToContactId();

    /**
     * @param int $contactId
     *
     * @return pocketlistsNotificationContentInterface
     */
    public function setToContactId($contactId);
}
