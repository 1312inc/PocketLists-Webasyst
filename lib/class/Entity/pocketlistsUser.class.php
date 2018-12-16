<?php

class pocketlistsUser
{
    /**
     * @var waContact
     */
    private $contact;

    /**
     * pocketlistsUser constructor.
     *
     * @param waContact $contact
     */
    public function __construct(waContact $contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return waContact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return int
     */
    public function hasLinkedApps()
    {
        /** @var pocketlistsItemLinkInterface[] $apps */
        $apps = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp();

         if (!$apps) {
            return 0;
        }

        /** @var pocketlistsItemLinkInterface $app */
        foreach ($apps as $app) {
            if ($app->userCanAccess($this)) {
                return 1;
            }
        }

        return 0;
    }
}
