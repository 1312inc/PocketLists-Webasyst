<?php

class pocketlistsSystemGetWebsocketUrlMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $channel = $this->get('channel');

        if (isset($channel)) {
            if (!is_string($channel)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid data type: “%s”', 'channel'), 400);
            }
            $channel = trim($channel);
            if (empty($channel)) {
                $channel = null;
            }
        }

        try {
            $ws = pocketlistsWebSoket::getInstance();
            $ws_url = $ws->getWebsocketUrl($channel);
        } catch (waException $e) {
            throw new pocketlistsApiException($e->getMessage(), 400);
        }

        $this->response['data'] = [
            'url'     => $ws_url,
            'channel' => $channel,
        ];
    }
}
