<?php

/**
 * Class pocketlistsPocketSettingsDialogAction
 */
class pocketlistsPocketSettingsDialogAction extends pocketlistsViewPocketAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        try {
            $pocket = $this->getPocket();
        } catch (pocketlistsNotFoundException $ex) {
            /** @var pocketlistsPocket $pocket */
            $pocket = pl2()->getEntityFactory(pocketlistsPocket::class)->createNew();
        }

        /**
         * UI in main sidebar
         * @event backend_pocket_dialog
         *
         * @param pocketlistsEventInterface $event Event object with pocketlistsPocket object (new or existing)
         * @return string HTML output
         */
        $event = new pocketlistsEvent(pocketlistsEventStorage::WA_POCKET_DIALOG, $pocket);
        $eventResult = pl2()->waDispatchEvent($event);

        $this->view->assign(['pocket' => $pocket, 'backend_pocket_dialog' => $eventResult]);
    }
}
