<?php

/**
 * Class pocketlistsListEmailController
 */
class pocketlistsListEmailController extends pocketlistsJsonController
{
    /**
     * @var array
     */
    private $data = [];

    /**
     *
     */
    public function execute()
    {
        $mail = waRequest::post('mail', false);
        if ($this->prepare($mail)) {
            (new pocketlistsBaseNotification())->sendMailInstantly($this->data);
        }
    }

    /**
     * @param $data
     *
     * @return bool
     */
    private function prepare($data)
    {
        $ev = new waEmailValidator();
        if (!$ev->isValid($data['to'])) {
            $this->errors = 'wrong email';

            return false;
        }

        $this->data = [
            'to'      => $data['to'],
            'subject' => 'string:'.$data['subject'],
            'body'    => 'string:'.nl2br($data['body']),
        ];

        return true;
    }
}
