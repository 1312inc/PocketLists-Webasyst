<?php
class pocketlistsEmptyController extends waViewController
{
    public function execute()
    {
        $this->setLayout(new pocketlistsEmptyLayout());
    }
}
