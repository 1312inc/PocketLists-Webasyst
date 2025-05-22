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
        $user_rights = [];
        $labels = [];
        $shortcuts = [];
        $is_premium = pocketlistsLicensing::isPremium();
        if (wa()->whichUI(pocketlistsHelper::APP_ID) != '1.3') {
            try {
                $user_get_list = new pocketlistsUsersGetMethod();
                $response = $user_get_list->getResponse(true, true);
                $users = ifset($response, 'data', []);
            } catch (pocketlistsApiException $pex) {
                $users = null;
            }

            $pocket_get_list = new pocketlistsPocketsGetMethod();
            $response = $pocket_get_list->getResponse(true);
            $pockets = ifset($response, 'data', []);

            $location_get_list = new pocketlistsLocationsGetMethod();
            $response = $location_get_list->getResponse(true);
            $locations = ifset($response, 'data', []);

            list($user, $count) = pocketlistsApiAbstractMethod::getTeammates([pl2()->getUser()->getId()]);
            $user = reset($user);

            $user_rights = pocketlistsSystemGetSettingsMethod::getUserRights();
            if ($is_premium || class_exists('pocketlistsProPlugin')) {
                $labels = pl2()->getModel(pocketlistsLabel::class)->getAllWithSort();
                $shortcuts = pl2()->getModel(pocketlistsShortcut::class)->select('*')->order('`group` ASC, id ASC')->fetchAll();
                foreach ($labels as &$label) {
                    $label = [
                        'id'   => (int) $label['id'],
                        'sort' => (int) $label['sort']
                    ] + $label;
                }
                foreach ($shortcuts as &$shortcut) {
                    $shortcut = [
                        'id'    => (int) $shortcut['id'],
                        'group' => (int) $shortcut['group']
                    ] + $shortcut;
                }
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
            'user_rights' => waUtils::jsonEncode($user_rights),
            'user_locale' => wa()->getLocale(),
            'user_timezone' => (empty($user_tz) ? 'auto' : $user_tz),
            'user' => waUtils::jsonEncode($user),
            'labels' => waUtils::jsonEncode($labels),
            'shortcuts' => waUtils::jsonEncode($shortcuts),
            'timestamp' => $current_time,
            'datetime' => pocketlistsHelper::convertDateToISO8601(date('Y-m-d H:i:s', $current_time)),
            'account_name' => wa()->accountName(),
            'framework_version' => wa()->getVersion('webasyst'),
            'app_version' => wa()->getVersion(pocketlistsHelper::APP_ID),
            'is_premium' => ($is_premium ? 1 : 0),
            'pl_debug_mode' => (wa()->getSetting('pl_debug_mode', 0) ? 1 : 0)
        ]);
    }
}
