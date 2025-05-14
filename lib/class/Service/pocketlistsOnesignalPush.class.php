<?php

class pocketlistsOnesignalPush extends onesignalPush
{
    /** @var pocketlistsPushClientModel */
    private $push_client_model;

    /** @var string|null */
    private $api_app_id;

    public function __construct($api_app_id = '')
    {
        $this->push_client_model = new pocketlistsPushClientModel();
        $this->api_app_id = $api_app_id;
    }

    public function getId()
    {
        return 'onesignal';
    }

    /**
     * @param array|int $contact_id
     * @param array $data
     * @return array
     * @throws waException
     */
    public function sendByContact($contact_id, $data)
    {
        $request_data = $this->prepareRequestData($data);

        $client_ids = $this->push_client_model->getByField('contact_id', $contact_id, true) ?: [];
        pocketlistsLogger::debug('Send to client ids:'.implode(',', array_column($client_ids, 'contact_id')));

        if (!$client_ids) {
            pocketlistsLogger::debug('No client ids');

            return [];
        }

        $push_data = $request_data;
        $push_data['app_id'] = $this->api_app_id;
        $push_data['include_player_ids'] = array_column($client_ids, 'client_id');

        if (isset($push_data['data']['badge']['total'])) {
            $push_data['ios_badgeType'] = 'SetTo';
            $push_data['ios_badgeCount'] = $push_data['data']['badge']['total'];
        }

        $response = $this->request('notifications', $push_data, waNet::METHOD_POST);
        pocketlistsLogger::debug($response);

        if (isset($response['errors']['invalid_player_ids'])) {
            $this->push_client_model->deleteById($response['errors']['invalid_player_ids']);
        }

        return $response;
    }

    public function isEnabled(): bool
    {
        return !empty($this->api_app_id);
    }

    protected function getNet()
    {
        if ($this->net === null) {
            $options = ['format' => waNet::FORMAT_JSON];
            $custom_headers = ['timeout' => 5];

            $this->net = new waNet($options, $custom_headers);
        }

        return $this->net;
    }
}
