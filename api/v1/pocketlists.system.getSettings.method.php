<?php

class pocketlistsSystemGetSettingsMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $current_time = time();

        $this->response['data'] = [
            'base_url'          => $this->getBaseUrl(),
            'user_locale'       => $this->getLocale(),
            'user_timezone'     => $this->getTimezone(),
            'timestamp'         => $current_time,
            'datetime'          => $this->getDatetime($current_time),
            'framework_version' => $this->getFrameworkVersion(),
            'app_version'       => $this->getAppVersion(),
            'is_premium'        => $this->isPremium(),
            'is_debug_mode'     => $this->isDebugMode(),
        ];
    }

    /**
     * @return string
     * @throws waException
     */
    private function getBaseUrl()
    {
        return  wa()->getUrl(true).wa()->getConfig()->getBackendUrl().'/'.pocketlistsHelper::APP_ID.'/';
    }

    /**
     * @return string|null
     * @throws waException
     */
    private function getLocale()
    {
        return wa()->getLocale();
    }

    /**
     * @return array|int|mixed|string
     * @throws waException
     */
    private function getTimezone()
    {
        $user_tz = wa()->getUser()->get('timezone');

        return (empty($user_tz) ? 'auto' : $user_tz);
    }

    /**
     * @param $time
     * @return string|null
     */
    private function getDatetime($time)
    {
        return $this->formatDatetimeToISO8601(date('Y-m-d H:i:s', $time));
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    private function getFrameworkVersion()
    {
        return wa()->getVersion('webasyst');
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    private function getAppVersion()
    {
        return wa()->getVersion(pocketlistsHelper::APP_ID);
    }

    /**
     * @return bool
     */
    private function isPremium()
    {
        return pocketlistsLicensing::isPremium();
    }

    /**
     * @return bool
     * @throws waDbException
     * @throws waException
     */
    private function isDebugMode()
    {
        return !!wa()->getSetting('pl_debug_mode', 0);
    }
}
