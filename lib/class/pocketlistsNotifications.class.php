<?php

class pocketlistsNotifications
{
    public static function sendMail($data)
    {
        $contact = new waContact($data['contact_id']);
        $to = $contact->get('email', 'default'); // todo: add email option in settings
        $view = wa()->getView();
        $view->clearAllAssign();

        $view->assign('name', $contact->getName());
        foreach ($data['variables'] as $var_name => $var_value) {
            $view->assign($var_name, $var_value);
        }

        $subject = $view->fetch($data['subject']);
        $body = $view->fetch($data['body']);

        $message = new waMailMessage($subject, $body);
        $message->setTo($to);
        // todo: settings?
        $message->setFrom('pocketlists@webasyst.ru', 'Pocketlists Notifier');

        if ($message->send()) {
        }
    }
}