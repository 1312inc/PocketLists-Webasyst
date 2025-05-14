<?php

class pocketlistsWsUrlController extends pocketlistsJsonController
{
    public function execute()
    {
        $channel = waRequest::get('channel', pocketlistsWebSoket::DEFAULT_CHANNEL, waRequest::TYPE_STRING_TRIM);

        try {
            $ws_url = pocketlistsWebSoket::getInstance()->getWebsocketUrl($channel);
        } catch (waException $e) {
            $this->errors = [
                'error_code' => 'error_ws',
                'error_description' => $e->getMessage()
            ];
            return;
        }
        $this->response = [
            'ws_url' => $ws_url
        ];
    }
}
