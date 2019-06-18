<?php

/**
 * Class pocketlistsContactsProfileTabHandler
 */
class pocketlistsContactsProfileTabHandler extends waEventHandler
{
    /**
     * @param $params
     *
     * @return mixed|void|null
     * @throws waException
     */
    public function execute(&$params)
    {
        if ($this->isOldContacts()) {
            return;
        }

        $contact_id = $params;

        $old_app = wa()->getApp();
        wa(pocketlistsHelper::APP_ID, 1);

        if (!pocketlistsRBAC::canAssign()) {
            return;
        }

        /** @var pocketlistsContactFactory $contactFactory */
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);

        /** @var pocketlistsContact $user */
        $user = $contactFactory->createNewWithId($contact_id);

        $has_access_app = wa()->getUser()->getRights(pocketlistsHelper::APP_ID, 'backend');

        if (!$has_access_app) {
            return;
        }

        $backend_url = pl2()->getBackendUrl(true);

        $result = [];

        // Invoices
        $result[] = [
            'id'    => 'pl2items',
            'title' => _w('Pocket Lists'),
            'url'   => sprintf('%spocketlists/?module=team&teammate=%s&external=1', $backend_url, $user->getLogin()),
        ];

        wa($old_app, 1);

        return ifempty($result, null);
    }

    /**
     * @return bool
     */
    protected function isOldContacts()
    {
        $is_old_contacts = waRequest::request('module', '', 'string') == 'contacts'
            && waRequest::request('action', '', 'string') == 'info'
            && wa()->appExists('contacts');
        if ($is_old_contacts) {
            $is_old_contacts = version_compare(wa()->getVersion('contacts'), '1.2.0') < 0;
        }

        return $is_old_contacts;
    }
}
