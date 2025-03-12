<?php

class pocketlistsPushDisableMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_POST;

    public function execute()
    {
        $data = $this->readBodyAsJson();

        $client_id = ifempty($data, 'client_id', null);
        if (empty($client_id)) {
            throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'client_id'), 400);
        } elseif (!is_string($client_id)) {
            throw new pocketlistsApiException(sprintf_wp('Type error parameter: “%s”.', 'client_id'), 400);
        }

        $data = [
            'client_id'   => $client_id,
            'contact_id'  => $this->getUser()->getId(),
        ];

        if (!pl2()->getModel(pocketlistsPushClient::class)->deleteByField($data)) {
            $this->setError(_w('Disable failed'));
        }

        $this->response['data'] = $this->singleFilterFields($data, ['client_id']);
    }
}
