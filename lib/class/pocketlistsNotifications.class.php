<?php

class pocketlistsNotifications
{
    public function sendMail($data)
    {
        $contact = new waContact($data['contact_id']);
        $to = $contact->get('email', 'default'); // todo: add email option in settings
        $view = wa()->getView();

        $subject = $view->fetch('string:'.$data['subject']);
        $body = $view->fetch('string:'.$data['body']);

        $message = new waMailMessage($subject, $body);
        $message->setTo($to);

        if ($message->send()) {
        }
    }
}