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
    public function defaultAction()
    {
        if (!$this->getUser()->isAdmin('pocketlists')) {
            throw new waRightsException(_ws('Access denied'));
        }
        if (wa()->whichUI() === '1.3') {
            if (!waRequest::isXMLHttpRequest()) {
                $this->redirect(wa()->getAppUrl(null, true));
            }
        } else {
            $this->setLayout(new pocketlistsStaticLayout());
        }
        parent::defaultAction();
    }
}
