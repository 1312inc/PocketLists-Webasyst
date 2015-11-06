<?php
class pocketlistsBackendController extends waViewController
{
    public function execute()
    {
        $this->setLayout(new pocketlistsDefaultLayout());
    }
}
