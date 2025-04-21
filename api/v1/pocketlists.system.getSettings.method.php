<?php

class pocketlistsSystemGetSettingsMethod extends pocketlistsApiAbstractMethod
{
    private $is_premium;

    public function execute()
    {
        $current_time = time();

        $this->response['data'] = [
            'base_url'          => $this->getBaseUrl(),
            'user_rights'       => self::getUserRights(),
            'user_locale'       => $this->getLocale(),
            'user_timezone'     => $this->getTimezone(),
            'user'              => $this->getCurrentUser(),
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

    public static function getUserRights()
    {
        $rights = pocketlistsRBAC::getUserRights();
        $rights['apps'] = [];

        if (wa()->appExists(pocketlistsAppLinkShop::APP)) {
            $user_rights = wa()->getUser()->getRights('pocketlists');
            if (
                (isset($user_rights['backend']) && $user_rights['backend'] > 1)
                || !empty($user_rights[pocketlistsRBAC::CAN_USE_SHOP_SCRIPT])
            ) {
                $rights['apps'][] = pocketlistsAppLinkShop::APP;
            }
        }
        if (wa()->appExists(pocketlistsAppLinkTasks::APP)) {
            $user_rights = wa()->getUser()->getRights(pocketlistsAppLinkTasks::APP);
            if (isset($user_rights['backend']) && $user_rights['backend'] > 1) {
                $rights['apps'][] = pocketlistsAppLinkTasks::APP;
            }
        }

        return $rights;
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

    protected function getCurrentUser()
    {
        list($result, $count) = self::getTeammates([$this->getUser()->getId()]);

        return $this->singleFilterFields(
            reset($result),
            [
                'id',
                'name',
                'username',
                'photo_url',
                'user_pic',
                'status',
                'team_role',
                'login',
                'me',
                'exists',
                'last_activity',
                'email',
                'locale',
                'extended_data'
            ], [
                'id' => 'int',
                'me' => 'bool',
                'exists' => 'bool',
                'last_activity' => 'datetime',
            ]
        );
    }

    private function getLabels()
    {
        $result = [];
        if ($this->isPremium()) {
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
        if ($this->isPremium()) {
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
