<?php

class pocketlistsSystemGetWebsocketUrlMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $channel = $this->get('channel');

        if (isset($channel)) {
            if (!is_string($channel)) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => sprintf_wp('Invalid type %s', 'channel'),
                    'data'        => []
                ];
                return;
            }
            $channel = trim($channel);
            if (empty($channel)) {
                $channel = null;
            }
        }

        try {
            $ws_url = pocketlistsWebSoket::getInstance()->getWebsocketUrl($channel);
        } catch (waException $e) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => $e->getMessage(),
                'data'        => []
            ];
            return;
        }

        $this->response['data'] = [
            'url'     => $ws_url,
            'channel' => $channel,
        ];
    }
}
