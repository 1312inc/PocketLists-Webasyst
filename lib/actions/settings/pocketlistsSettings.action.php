<?php

/**
 * Class pocketlistsSettingsAction
 */
class pocketlistsSettingsAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $settings = $this->user->getSettings()->getAllSettings();
        $this->view->assign('settings', $settings);

//        $inbox_list_id = $this->user->getSettings()->getStreamInboxList();
//        if ($inbox_list_id) {
//            /** @var pocketlistsListFactory $listFactory */
//            $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
//            /** @var pocketlistsList $inbox_list */
//            $inbox_list = $listFactory->findById($inbox_list_id);
//
//            $this->view->assign(
//                [
//                    'inbox_lists' => $listFactory->findAllActive(),
//                    'inbox_list'  => $inbox_list,
//                ]
//            );
//        }

        /**
         * UI hook in backend settings page
         * @event backend_settings
         *
         * @param pocketlistsEventInterface $event
         * @return string html output
         */
        $event = new pocketlistsEvent(pocketlistsEventStorage::WA_BACKEND_SETTINGS);
        $eventResult = pl2()->waDispatchEvent($event);

        $asp = new waAppSettingsModel();
        $this->view->assign(
            [
                'last_recap_cron_time' => $asp->get(wa()->getApp(), 'last_recap_cron_time'),
                'cron_command'         => pl2()->getCronJob('recap_mail'),
                'admin'                => pocketlistsRBAC::isAdmin(),
                'backend_settings'     => $eventResult,
                'isAdmin'              => $this->getUser()->isAdmin('pocketlists'),
            ]
        );
    }
}
