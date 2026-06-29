<?php

class pocketlistsServicesApi extends installerServicesApi
{
    public function isConnected()
    {
        static $result;
        if (is_null($result)) {
            $result = parent::isConnected();
        }

        return $result;
    }
}
