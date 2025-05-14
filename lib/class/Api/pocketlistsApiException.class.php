<?php

class pocketlistsApiException extends waAPIException
{
    public function __construct($error_description = null, $status_code = null, $response = [])
    {
        parent::__construct($error_description, $error_description, $status_code);
        $this->code = ((empty($status_code) || !is_numeric($status_code)) ? 500 : (int) $status_code);
        $this->response = [
            'status_code' => 'error',
            'error'       => $error_description,
            'data'        => $response
        ];
    }
}
