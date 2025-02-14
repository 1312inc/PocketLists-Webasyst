<?php

class pocketlistsWebSoket
{
    const DEFAULT_CHANNEL = 'live';

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
     * @param $channel
     * @return mixed
     * @throws waException
     */
    public function getWebsocketUrl($channel = null)
    {
        if ($this->services_api->isConnected()) {
            $ws_url = $this->services_api->getWebsocketUrl($channel ?? self::DEFAULT_CHANNEL);
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
        if (!empty($data) && $this->services_api->isConnected()) {
            try {
                $this->services_api->sendWebsocketMessage($data, $channel ?? self::DEFAULT_CHANNEL);
            } catch (Throwable $e) {
            }
        }
    }
}
