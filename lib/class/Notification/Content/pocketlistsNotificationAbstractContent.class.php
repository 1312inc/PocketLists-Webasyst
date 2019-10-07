<?php

/**
 * Class pocketlistsNotificationAbstractContent
 */
abstract class pocketlistsNotificationAbstractContent implements pocketlistsNotificationContentInterface, pocketlistsHydratableInterface, JsonSerializable
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @var int
     */
    protected $toContactId;

    /**
     * pocketlistsNotificationAbstractContent constructor.
     *
     * @param null $json
     */
    public function __construct($json = null)
    {
        if ($json) {
            $this->extractJson($json);
        }
    }

    /**
     * @param string $json
     */
    public function extractJson($json)
    {
        $data = json_decode($json, true);

        pl2()->getHydrator()->hydrate($this, $data);
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return int
     */
    public function getToContactId()
    {
        return $this->toContactId;
    }

    /**
     * @param int $toContactId
     *
     * @return pocketlistsNotificationContentInterface
     */
    public function setToContactId($toContactId)
    {
        $this->toContactId = $toContactId;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return mixed|void
     */
    public function afterHydrate($data = [])
    {
    }

    /**
     * @param array $fields
     *
     * @return array|void
     */
    public function beforeExtract(array &$fields)
    {
    }

    /**
     * @param array $fields
     *
     * @return array
     */
    public function afterExtract(array &$fields)
    {
    }

    /**
     * @param $user_id
     *
     * @return mixed|string
     * @throws waException
     */
    protected function getBackendUrl($user_id)
    {
        /** @var waContactSettingsModel $us */
        $us = pl2()->getModel('waContactSettings');
        //wa()->getRootUrl(true).wa()->getConfig()->getBackendUrl()
        return $us->getOne($user_id, 'webasyst', 'backend_url');
    }
}