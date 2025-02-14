<?php

class pocketlistsServicesApi extends installerServicesApi
{
    const TIME_OUT = 5;

    public function isConnected()
    {
        try {
            $url = $this->provider->getServiceUrl(self::WS_CONNECT_SERVICE);
            $net = new waNet([
                'timeout' => self::TIME_OUT
            ]);

            /** ping request */
            $net->query($url);
        } catch (waNetTimeoutException $wa_net) {
            return false;
        } catch (waNetException $wa_net) {
        }

        return parent::isConnected();
    }
}
