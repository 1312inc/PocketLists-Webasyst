<?php

class pocketlistsPushEnableMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_POST;

    public function execute()
    {
        $data = $this->readBodyAsJson();

        $client_id = ifempty($data, 'client_id', null);
        if (empty($client_id)) {
            throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'client_id'), 400);
        } elseif (!is_string($client_id)) {
            throw new pocketlistsApiException(sprintf_wp('Invalid data type: “%s”', 'client_id'), 400);
        }

        $data = [
            'client_id'       => $client_id,
            'contact_id'      => $this->getUser()->getId(),
            'api_token'       => $this->getApiToken($client_id),
            'create_datetime' => date('Y-m-d H:i:s'),
        ];

        if (!pl2()->getModel(pocketlistsPushClient::class)->insert($data, waModel::INSERT_IGNORE)) {
            $this->setError(_w('Enable failed'));
        }

        $this->response['data'] = $this->singleFilterFields(
            $data,
            [
                'client_id',
                'contact_id',
                'create_datetime',
            ], [
                'contact_id' => 'int',
                'create_datetime' => 'datetime'
            ]
        );
    }
}
