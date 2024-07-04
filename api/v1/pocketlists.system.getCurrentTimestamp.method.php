<?php

class pocketlistsSystemGetCurrentTimestampMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_GET;

    public function execute()
    {
        $this->response = time();
    }
}
