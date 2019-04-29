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

        $this->view->assign(compact('pocket'));
    }
}
