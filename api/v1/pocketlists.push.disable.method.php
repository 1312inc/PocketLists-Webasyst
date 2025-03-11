<?php

class pocketlistsPushDisableMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_POST;

    public function execute()
    {
        $data = $this->readBodyAsJson();



        $data = [
            'client_id'       => ifempty($data, 'client_id', null),
            'contact_id'      => $this->getUser()->getId(),
            'create_datetime' => date('Y-m-d H:i:s'),
        ];

        $this->response['data'] = $data;
    }
}
