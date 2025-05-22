<?php

class pocketlistsApiAiRequest
{
    const FIELDS_CACHE_TTL = 3600; // 1 hour

    /**
     * @return mixed
     * @throws pocketlistsApiException
     * @throws waException
     */
    public function generate(string $facility, string $prompt)
    {
        $cache = new waVarExportCache(
            'ai_'.$facility.md5($prompt),
            self::FIELDS_CACHE_TTL,
            pocketlistsHelper::APP_ID
        );
        $api_call = $cache->get();
        if (!$api_call) {
            $api = new waServicesApi();
            if (!$api->isConnected()) {
                return $this;
            }
            $api_call = $api->serviceCall('AI', [
                'facility'  => $facility,
                'objective' => $prompt,
                'locale'    => wa()->getLocale()
            ], 'POST');
            if (empty($api_call['response']) || empty($api_call['response'][$facility])) {
                throw new pocketlistsApiException('Unexpected response from WAID API');
            }
            $cache->set($api_call);
        }

        return ifset($api_call, 'response', $facility, []);
    }
}
