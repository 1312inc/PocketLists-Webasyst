<?php

class pocketlistsApiAiRequest
{
    const FIELDS_CACHE_TTL = 3600; // 1 hour

    private $facility = null;
    private $values = [];

    /**
     * @param string $facility
     * @return $this
     * @throws pocketlistsApiException
     * @throws waException
     */
    public function loadFieldsFromApi(string $facility): pocketlistsApiAiRequest
    {
        $this->facility = $facility;
        $cache = new waVarExportCache('ai_fields_'.$facility, self::FIELDS_CACHE_TTL, pocketlistsHelper::APP_ID);
        $api_call = $cache->get();
        if (!$api_call) {
            $api = new waServicesApi();
            if (!$api->isConnected()) {
                return $this;
            }
            $api_call = $api->serviceCall('AI_OVERVIEW', [
                'locale'   => wa()->getLocale(),
                'facility' => $facility,
            ]);
            if (empty($api_call['response'])) {
                throw new pocketlistsApiException('Unexpected response from WAID API');
            }
            $cache->set($api_call);
        }

        return $this;
    }

    /**
     * @return mixed
     * @throws pocketlistsApiException
     * @throws waException
     */
    public function generate()
    {
        if (!$this->facility) {
            throw new pocketlistsApiException('loadFieldsFromApi() must be called before generate()');
        }
        $api = new waServicesApi();
        if (!$api->isConnected()) {
            throw new pocketlistsApiException('WAID is not connected');
        }

        $request_data = $this->values;
        $request_data['facility'] = $this->facility;
        $api_call = $api->serviceCall('AI', $request_data, 'POST');
        if (empty($api_call['response'])) {
            throw new pocketlistsApiException('Unexpected response from WAID API');
        }

        return $api_call['response'];
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setFieldValues(array $values): pocketlistsApiAiRequest
    {
        foreach ($values as $k => $v) {
            $this->setFieldValue($k, $v);
        }

        return $this;
    }

    /**
     * @param $field_id
     * @param $value
     * @return $this
     */
    public function setFieldValue($field_id, $value): pocketlistsApiAiRequest
    {
        $this->values[$field_id] = $value;

        return $this;
    }
}
