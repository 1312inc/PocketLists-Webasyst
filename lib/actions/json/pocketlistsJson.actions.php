<?php

class pocketlistsJsonActions extends waJsonActions
{
    public function defaultAction()
    {
        throw new waException('Unknown action.');
    }

    public function AppCountAction()
    {
        $pi = new pocketlistsItemModel();
        $this->response = $pi->getAppCountForUser();
    }
}