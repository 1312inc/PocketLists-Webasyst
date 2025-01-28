<?php

/**
 * Class pocketlistsDefaultLayout
 */
class pocketlistsDefaultLayout extends waLayout
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $us = new pocketlistsUserSettings();
        if ($us->appIcon() === false) {
            $us->saveDefaults();
        }
        $this->executeAction('sidebar', new pocketlistsBackendSidebarAction());

        /**
         * @event backend_head
         *
         * @param pocketlistsEventInterface $event
         * @return string HTML output
         */
        $event = new pocketlistsEvent(pocketlistsEventStorage::WA_BACKEND_HEAD);
        $eventResult = pl2()->waDispatchEvent($event);

        // API token for SPA
        $token = (new waApiTokensModel())->getToken(
            pocketlistsConfig::API_CLIENT_ID,
            wa()->getUser()->getId(),
            pocketlistsConfig::API_TOKEN_SCOPE
        );

        $user_get_list = new pocketlistsUsersGetMethod();
        $response = $user_get_list->getResponse(true);
        $users = ifset($response, 'data', []);

        $pocket_get_list = new pocketlistsPocketsGetMethod();
        $response = $pocket_get_list->getResponse(true);
        $pockets = ifset($response, 'data', []);

        $location_get_list = new pocketlistsLocationsGetMethod();
        $response = $location_get_list->getResponse(true);
        $locations = ifset($response, 'data', []);

        $user_tz = wa()->getUser()->get('timezone');
        $current_time = time();

        $this->view->assign([
            pocketlistsEventStorage::WA_BACKEND_HEAD => $eventResult,
            'isAdmin' => (int) pocketlistsRBAC::isAdmin(),
            'spa_api_token' => $token,
            'is_premium' => (pocketlistsLicensing::isPremium() ? 1 : 0),
            'users' => waUtils::jsonEncode($users),
            'pockets' => waUtils::jsonEncode($pockets),
            'locations' => waUtils::jsonEncode($locations),
            'user_locale' => wa()->getLocale(),
            'user_timezone' => (empty($user_tz) ? 'auto' : $user_tz),
            'timestamp' => $current_time,
            'datetime' => pocketlistsHelper::convertDateToISO8601(date('Y-m-d H:i:s', $current_time)),
            'framework_version' => wa()->getVersion('webasyst'),
            'pl_debug_mode' => (wa()->getSetting('pl_debug_mode', 0) ? 1 : 0)
        ]);
    }
}
