<?php

/**
 * Class pocketlistsNotificationsAboutNewItems
 */
class pocketlistsNotification
{
    /**
     * @var pocketlistsList[]
     */
    private static $lists = [];

    /**
     * @var pocketlistsListModel
     */
    private $lm;

    /**
     * @param      $data
     * @param bool $backend_url
     */
    public static function sendMail($data, $backend_url = false)
    {
        try {
            $default_variables = [
                'email_settings_url' => '#/settings/',
            ];

            if (empty($data['variables'])) {
                $data['variables'] = [];
            }
            $data['variables'] = array_merge($default_variables, $data['variables']);

            $to = false;
            $view = wa()->getView();
            $view->clearAllAssign();
            $view->clearAllCache();

            if (isset($data['contact_id'])) {
                $contact = new waContact($data['contact_id']);
                $to = $contact->get('email', 'default'); // todo: add email option in settings
                $view->assign('name', $contact->getName());
                $view->assign('now', waDateTime::date("Y-m-d H:i:s", time(), $contact->getTimezone()));
            } elseif (isset($data['to'])) {
                $to = $data['to'];
            }

            if (!$to) {
                return;
            }

            $absolute_backend_url = $backend_url
                ? $backend_url
                : pl2()->getRootUrl(true).pl2()->getBackendUrl();

            $view->assign('backend_url', rtrim($absolute_backend_url, '/').'/pocketlists/');
            if (isset($data['variables'])) {
                foreach ($data['variables'] as $var_name => $var_value) {
                    $view->assign($var_name, $var_value);
                }
            }

            $subject = $view->fetch($data['subject']);
            $body = $view->fetch($data['body']);

            $message = new waMailMessage($subject, $body);
            $message->setTo($to);
// todo: settings?
//        $message->setFrom('pocketlists@webasyst.ru', 'Pocketlists Notifier');

            if (!$message->send()) {
                pocketlistsHelper::logError(sprintf('Email send error to %s', $to));
            }
        } catch (waException $ex) {
            pocketlistsHelper::logError(sprintf('Email send error to %s', $to), $ex);
        }
    }

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsList|pocketlistsNullList
     * @throws waException
     */
    protected function getList($item)
    {
        if (!isset(self::$lists[$item->getListId()])) {
            if ($item->getListId()) {
                self::$lists[$item->getListId()] = $item->getList();
            } else {
                self::$lists[$item->getListId()] = (new pocketlistsNullList())->setName(_w('Stream'));
            }
        }

        return self::$lists[$item->getListId()];
    }

    /**
     * @param $user_id
     *
     * @return mixed|string
     */
    protected function getBackendUrl($user_id)
    {
        $us = new waContactSettingsModel();

        return $us->getOne($user_id, 'webasyst', 'backend_url');
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return bool
     */
    protected function canSend(pocketlistsContact $contact)
    {
        return $contact->isExists();
    }
}
