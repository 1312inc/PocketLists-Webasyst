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
     * @var string
     */
    private $error;

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
        $data = json_decode($json, true);

        pl2()->getHydrator()->hydrate($this, $data);
    }

    /**
     * @return bool
     */
    public function send()
    {
        try {
            $default_variables = [
                'default'          => [
                    'email_settings_url' => '#/settings/',
                    'account_name' => wa()->accountName(),
                ],
            ];

            $data = array_merge($default_variables, $this->getParams());

            $to = false;

            $view = wa()->getView();
            $view->clearAllAssign();
            $view->clearAllCache();

            $to = $this->getToEmail();

            if (!$to) {
                $this->error = 'Notification doesn`t have email';

                return false;
            }

            if ($this->getToContactId()) {
                $contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($this->getToContactId());
                if ($contact->isExists()) {
                    $view->assign('name', $contact->getName());
                    $view->assign(
                        'now',
                        waDateTime::date('Y-m-d H:i:s', time(), $contact->getContact()->getTimezone())
                    );
                } else {
                    $this->error = sprintf('Contact #%s doesn`t exist anymore (thanos snap)', $contact->getId());

                    return false;
                }
            }

            $absolute_backend_url = $this->getBackendUrl($this->getToContactId())
                ?: pl2()->getRootUrl(true).pl2()->getBackendUrl();

            $view->assign('backend_url', rtrim($absolute_backend_url, '/').'/pocketlists/');
            $view->assign($data);

            $subject = $view->fetch($this->getSubject());
            $body = $view->fetch($this->getTemplate());

            $message = new waMailMessage($subject, $body);
            $message->setTo($to);
// todo: settings?
//        $message->setFrom('pocketlists@webasyst.ru', 'Pocketlists Notifier');

            if (!$message->send()) {
                $this->error = sprintf('Email send error to %s', $to);
                pocketlistsHelper::logError($this->error);

                return false;
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

            return true;
        } catch (waException $ex) {
            $this->error = sprintf('Email send error to %s', $to);
            pocketlistsHelper::logError($this->error, $ex);
        }

        return false;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
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