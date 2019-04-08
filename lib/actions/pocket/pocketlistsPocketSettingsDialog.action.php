<?php

/**
 * Class pocketlistsPocketSettingsDialogAction
 */
class pocketlistsPocketSettingsDialogAction extends pocketlistsViewPocketAction
{
    /**
     * @throws waException
     */
    public function execute()
    {
        try {
            $pocket = $this->getPocket();
        } catch (pocketlistsNotFoundException $ex) {
            /** @var pocketlistsPocket $pocket */
            $pocket = pl2()->getEntityFactory(pocketlistsPocket::class)->createNew();
        }

        $this->view->assign(compact('pocket'));
    }
}
