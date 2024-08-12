<?php

/**
 * Class pocketlistsStaticLayout
 */
class pocketlistsStaticLayout extends waLayout
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

        $this->view->assign([
            pocketlistsEventStorage::WA_BACKEND_HEAD => $eventResult,
            'isAdmin' => (int) pocketlistsRBAC::isAdmin(),
            'is_premium' => pocketlistsLicensing::isPremium(),
        ]);
    }
}
