<?php

/**
 * Class pocketlistsContactsProfileTab
 */
final class pocketlistsContactsProfileTab
{
    /**
     * @param $params
     *
     * @return mixed|void|null
     * @throws pocketlistsForbiddenException
     */
    public function execute(&$params)
    {
        if ($this->isOldContacts()) {
            return;
        }

        $contact_id = $params;

        try {
            $old_app = wa()->getApp();
            wa(pocketlistsHelper::APP_ID, 1);

            if (!pocketlistsRBAC::canAssign()) {
                throw new pocketlistsForbiddenException();
            }

            $has_access_app = wa()->getUser()->getRights(pocketlistsHelper::APP_ID, 'backend');

            if (!$has_access_app) {
                throw new pocketlistsForbiddenException();
            }

            /** @var pocketlistsContactFactory $contactFactory */
            $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);

            /** @var pocketlistsContact $user */
            $user = $contactFactory->createNewWithId($contact_id);

            if (!$user->getContact()->getRights(pocketlistsHelper::APP_ID)) {
                throw new pocketlistsForbiddenException();
            }

            $backend_url = pl2()->getBackendUrl(true);

            $result = [];

            $result[] = [
                'id' => 'pl2items',
                'title' => _w('Pocket Lists'),
                'url' => sprintf(
                    '%spocketlists/?module=team&teammate=%s&external=1&external_app=%s',
                    $backend_url,
                    $user->getLogin(),
                    $old_app
                ),
            ];
        } catch (waException $ex) {

        }

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