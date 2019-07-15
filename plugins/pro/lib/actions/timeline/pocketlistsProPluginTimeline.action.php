<?php


class pocketlistsProPluginTimelineAction extends pocketlistsProPluginAbstractViewAction
{
    public function runAction($params = null)
    {
        $this->view->assign([
            'params' => $this
        ]);
    }
}
