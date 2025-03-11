<?php

class pocketlistsPushEnableMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_POST;

    public function execute()
    {
        $data = $this->readBodyAsJson();

        if (empty($data)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($data)) {
            throw new pocketlistsApiException(_w('Type error data'), 400);
        } elseif (empty($data['client_id'])) {
            throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'client_id'), 400);
        } elseif (!is_string($data['client_id'])) {
            throw new pocketlistsApiException(sprintf_wp('Type error parameter: “%s”.', 'client_id'), 400);
        }

        $data = [
            'client_id'       => ifempty($data, 'client_id', null),
            'contact_id'      => $this->getUser()->getId(),
            'create_datetime' => date('Y-m-d H:i:s'),
        ];

        $this->response['data'] = $data;
    }
}
