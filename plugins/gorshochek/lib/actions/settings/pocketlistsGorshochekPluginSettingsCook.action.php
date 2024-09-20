<?php

class pocketlistsGorshochekPluginSettingsCookAction extends waViewAction
{
    const DEFAULT_COUNT = 100;

    public function execute()
    {
        /** @var pocketlistsContactFactory $contact_factory */
        $contact_factory = pl2()->getEntityFactory(pocketlistsContact::class);
        $teammates = $contact_factory->getTeammates(pocketlistsRBAC::getAccessContacts(), true, true, true);

        $this->view->assign([
            'curr_user' => wa()->getUser(),
            'all_users' => $teammates
        ]);
    }
}
