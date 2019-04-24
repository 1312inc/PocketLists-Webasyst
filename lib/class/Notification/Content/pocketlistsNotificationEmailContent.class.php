<?php

/**
 * Class pocketlistsNotificationEmailContent
 */
class pocketlistsNotificationEmailContent implements pocketlistsNotificationContentInterface, pocketlistsHydratableInterface
{
    /**
     * @var int
     */
    private $toContactId;

    /**
     * @var string
     */
    private $toEmail;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $template;

    /**
     * @var array
     */
    private $params;

    /**
     * @return int
     */
    public function getToContactId()
    {
        return $this->toContactId;
    }

    /**
     * @param int $contactId
     *
     * @return pocketlistsNotificationEmailContent
     */
    public function setToContactId($contactId)
    {
        $this->toContactId = $contactId;

        return $this;
    }

    /**
     * @return string
     */
    public function getToEmail()
    {
        return $this->toEmail;
    }

    /**
     * @param string $toEmail
     *
     * @return pocketlistsNotificationEmailContent
     */
    public function setToEmail($toEmail)
    {
        $this->toEmail = $toEmail;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return pocketlistsNotificationEmailContent
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     *
     * @return pocketlistsNotificationEmailContent
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return pocketlistsNotificationEmailContent
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        $data = pl2()->getHydrator()->extract(
            $this,
            [
                'subject',
                'template',
                'toContactId',
                'toEmail',
                'params',
            ]
        );

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param string $json
     */
    public function extractJson($json)
    {
        $data = json_decode($json);

        pl2()->getHydrator()->hydrate($this, $data);
    }

    public function send()
    {
        try {
            $default_variables = [
                'email_settings_url' => '#/settings/',
            ];

            $data = array_merge($default_variables, $this->getParams());

            $to = false;

            $view = wa()->getView();
            $view->clearAllAssign();
            $view->clearAllCache();

            if ($this->getToContactId()) {
                $contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($this->getToContactId());

                $to = $contact->getContact()->get('email', 'default'); // todo: add email option in settings

                $view->assign('name', $contact->getName());
                $view->assign('now', waDateTime::date('Y-m-d H:i:s', time(), $contact->getContact()->getTimezone()));
            } elseif ($this->getToEmail()) {
                $to = $this->getToEmail();
            }

            if (!$to) {
                return;
            }

            $absolute_backend_url = $this->getBackendUrl($this->getToContactId())
                ?: pl2()->getRootUrl(true).pl2()->getBackendUrl();

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

            pocketlistsHelper::logDebug(
                sprintf(
                    "Mail sent to %s\nSubject: %s\nBody: %s",
                    $to,
                    $subject,
                    $body
                ),
                'mail.log'
            );
        } catch (waException $ex) {
            pocketlistsHelper::logError(sprintf('Email send error to %s', $to), $ex);
        }
    }

    public function afterHydrate()
    {
        // TODO: Implement afterHydrate() method.
    }

    public function beforeExtract(array &$fields)
    {
        // TODO: Implement beforeExtract() method.
    }

    /**
     * @param $user_id
     *
     * @return mixed|string
     * @throws waException
     */
    protected function getBackendUrl($user_id)
    {
        /** @var waContactSettingsModel $us */
        $us = pl2()->getModel('waContactSettings');

        return $us->getOne($user_id, 'webasyst', 'backend_url');
    }
}