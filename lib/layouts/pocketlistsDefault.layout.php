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

        $user = [];
        $users = [];
        $pockets = [];
        $locations = [];
        $labels = [];
        $shortcuts = [];
        $is_premium = pocketlistsLicensing::isPremium();
        if (wa()->whichUI(pocketlistsHelper::APP_ID) != '1.3') {
            $user_get_list = new pocketlistsUsersGetMethod();
            $response = $user_get_list->getResponse(true);
            $users = ifset($response, 'data', []);
            foreach ($users as $u) {
                if ($u['me']) {
                    $user = $u;
                    break;
                }
            }

            $pocket_get_list = new pocketlistsPocketsGetMethod();
            $response = $pocket_get_list->getResponse(true);
            $pockets = ifset($response, 'data', []);

            $location_get_list = new pocketlistsLocationsGetMethod();
            $response = $location_get_list->getResponse(true);
            $locations = ifset($response, 'data', []);

            if ($is_premium) {
                $labels = pl2()->getModel(pocketlistsLabel::class)->getAllWithSort();
                $shortcuts = pl2()->getModel(pocketlistsShortcut::class)->select('*')->order('`group` ASC, id ASC')->fetchAll();
            }
        }

        $user_tz = wa()->getUser()->get('timezone');
        $current_time = time();

        $this->view->assign([
            pocketlistsEventStorage::WA_BACKEND_HEAD => $eventResult,
            'isAdmin' => (int) pocketlistsRBAC::isAdmin(),
            'spa_api_token' => $token,
            'users' => waUtils::jsonEncode($users),
            'pockets' => waUtils::jsonEncode($pockets),
            'locations' => waUtils::jsonEncode($locations),
            'user_rights' => waUtils::jsonEncode(pocketlistsRBAC::getUserRights()),
            'user_locale' => wa()->getLocale(),
            'user_timezone' => (empty($user_tz) ? 'auto' : $user_tz),
            'user' => waUtils::jsonEncode($user),
            'labels' => waUtils::jsonEncode($labels),
            'shortcuts' => waUtils::jsonEncode($shortcuts),
            'timestamp' => $current_time,
            'datetime' => pocketlistsHelper::convertDateToISO8601(date('Y-m-d H:i:s', $current_time)),
            'framework_version' => wa()->getVersion('webasyst'),
            'app_version' => wa()->getVersion(pocketlistsHelper::APP_ID),
            'is_premium' => ($is_premium ? 1 : 0),
            'pl_debug_mode' => (wa()->getSetting('pl_debug_mode', 0) ? 1 : 0)
        ]);
    }
}
