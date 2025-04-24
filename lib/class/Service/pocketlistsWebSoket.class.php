<?php

class pocketlistsWebSoket
{
    const DEFAULT_CHANNEL = 'live';
    const PREFIX_CHANNEL = 'pocketlists';

    private static $instance;

    /** @var waServicesApi */
    private $services_api;

    private function __construct()
    {}

    /**
     * @return pocketlistsWebSoket
     * @throws waException
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            if (!class_exists('waServicesApi')) {
                throw new waException(_w('Not implemented yet.'));
            }
            self::$instance = new self;
            self::$instance->services_api = new pocketlistsServicesApi();
        }

        return self::$instance;
    }

    /**
     * @param $user_id
     * @param $channel
     * @return string
     */
    public function getChannel($user_id, $channel = self::DEFAULT_CHANNEL)
    {
        return md5(pocketlistsWebSoket::PREFIX_CHANNEL.$user_id).$channel;
    }

    /**
     * @param $channel
     * @return mixed
     * @throws waException
     */
    public function getWebsocketUrl($channel = null)
    {
        if ($this->services_api->isConnected()) {
            $channel = $this->getChannel(wa()->getUser()->getId(), $channel);
            $ws_url = $this->services_api->getWebsocketUrl($channel);
            if (empty($ws_url)) {
                throw new waException(_w('Webasyst websocket API error.'));
            }
        } else {
            throw new waException(_w('Webasyst ID services are not connected.'));
        }

        return $ws_url;
    }

    /**
     * @param array $data
     * @param string $channel
     * @return void
     */
    public function sendWebsocketData($data, $channel = null)
    {
        if (!empty($data) && !empty($channel) && $this->services_api->isConnected()) {
            try {
                $this->services_api->sendWebsocketMessage(
                    $data,
                    $channel,
                    pocketlistsHelper::APP_ID
                );
            } catch (Throwable $e) {
            }
        }
    }
}
