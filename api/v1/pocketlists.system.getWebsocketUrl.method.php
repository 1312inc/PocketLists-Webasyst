<?php

class pocketlistsSystemGetWebsocketUrlMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $channel = $this->get('channel');

        if (isset($channel)) {
            if (!is_string($channel)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'channel'), 400);
            }
            $channel = trim($channel);
            if (empty($channel)) {
                $channel = null;
            }
        }

        try {
            $ws_url = pocketlistsWebSoket::getInstance()->getWebsocketUrl($channel);
        } catch (waException $e) {
            throw new waAPIException('error_ws', $e->getMessage(), 400);
        }

        $this->response = [
            'url'     => $ws_url,
            'channel' => $channel,
        ];
    }
}
