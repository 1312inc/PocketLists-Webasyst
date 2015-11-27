<?php

/**
 * A list of localized strings to use in JS.
 */
class pocketlistsBackendLocController extends waViewController
{
    public function execute()
    {
        $this->executeAction(new pocketlistsBackendLocAction());
    }

    public function preExecute()
    {
        // do not save this page as last visited
    }
}
