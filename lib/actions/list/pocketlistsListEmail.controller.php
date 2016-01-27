<?php

class pocketlistsListEmailController extends waJsonController
{
    private $data = array();

    public function execute()
    {
        $mail = waRequest::post('mail', false);
        if ($this->prepare($mail)) {
            pocketlistsNotifications::sendMail($this->data);
        }
    }

    private function prepare($data)
    {
        $ev = new waEmailValidator();
        if (!$ev->isValid($data['to'])) {
            $this->errors = 'wrong email';
            return false;
        };
        $this->data = array(
            'to' => $data['to'],
            'subject' => 'string:' . $data['subject'],
            'body' => 'string:' . nl2br($data['body'])
        );
        return true;
    }
}
