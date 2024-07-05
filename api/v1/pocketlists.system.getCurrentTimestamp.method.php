<?php

class pocketlistsSystemGetCurrentTimestampMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $this->response = time();
    }
}
