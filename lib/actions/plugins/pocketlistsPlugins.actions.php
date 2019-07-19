<?php

/**
 * Class pocketlistsPluginsActions
 */
class pocketlistsPluginsActions extends waPluginsActions
{
    /**
     * @var bool
     */
    protected $shadowed = true;

    /**
     * @throws waRightsException
     */
    public function preExecute()
    {
        if (!$this->getUser()->isAdmin('pocketlists')) {
            throw new waRightsException(_ws('Access denied'));
        }
    }
}
