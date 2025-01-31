<?php

/**
 * Class pocketlistsAboutAction
 */
class pocketlistsAboutAction extends pocketlistsViewAction
{
    public function runAction($params = null)
    {
        if (wa()->whichUI() !== '1.3') {
            $this->setLayout(new pocketlistsStaticLayout());
        }
    }
}
