<?php

class pocketlistsSystemGetSettingsMethod extends pocketlistsApiAbstractMethod
{
    private $is_premium;

    public function execute()
    {
        $current_time = time();

        $this->response['data'] = [
            'base_url'          => $this->getBaseUrl(),
            'user_rights'       => pocketlistsRBAC::getUserRights(),
            'user_locale'       => $this->getLocale(),
            'user_timezone'     => $this->getTimezone(),
            'labels'            => $this->getLabels(),
            'shortcuts'         => $this->getShortcuts(),
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

    private function getLabels()
    {
        $result = [];
        if ($this->is_premium) {
            $result = pl2()->getModel(pocketlistsLabel::class)->getAllWithSort();
        }

        return $this->filterFields(
            $result,
            ['id', 'name', 'color', 'sort'],
            ['id' => 'int', 'sort' => 'int']
        );
    }

    private function getShortcuts()
    {
        $result = [];
        if ($this->is_premium) {
            $result = pl2()->getModel(pocketlistsShortcut::class)->select('*')->order('`group` ASC, id ASC')->fetchAll();
        }

        return $this->filterFields(
            $result,
            ['id', 'name', 'group'],
            ['id' => 'int', 'group' => 'int']
        );
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
        if (!isset($this->is_premium)) {
            $this->is_premium = pocketlistsLicensing::isPremium();
        }

        return $this->is_premium;
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
