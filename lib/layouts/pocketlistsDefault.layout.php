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

        $user_get_list = new pocketlistsUserGetListMethod();
        $response = $user_get_list->getResponse(true);
        $users = ifset($response, 'data', []);

        $pocket_get_list = new pocketlistsPocketGetListMethod();
        $response = $pocket_get_list->getResponse(true);
        $pockets = ifset($response, 'data', []);
        $user_tz = wa()->getUser()->get('timezone');

        $this->view->assign([
            pocketlistsEventStorage::WA_BACKEND_HEAD => $eventResult,
            'isAdmin' => (int) pocketlistsRBAC::isAdmin(),
            'spa_api_token' => $token,
            'is_premium' => pocketlistsLicensing::isPremium(),
            'users' => waUtils::jsonEncode($users),
            'pockets' => waUtils::jsonEncode($pockets),
            'locale' => wa()->getLocale(),
            'timezone' => (empty($user_tz) ? 'auto' : $user_tz),
            'framework_version' => wa()->getVersion('webasyst')
        ]);
    }
}
