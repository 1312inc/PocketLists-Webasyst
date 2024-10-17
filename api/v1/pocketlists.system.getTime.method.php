<?php

class pocketlistsSystemGetTimeMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $current_time = time();
        $user_tz = wa()->getUser()->get('timezone');
        $user_tz = (empty($user_tz) ? 'auto' : $user_tz);

        $this->response = [
            'timestamp'     => $current_time,
            'datetime'      => $this->formatDatetimeToISO8601(date('Y-m-d H:i:s', $current_time)),
            'user_timezone' => $user_tz,
        ];
    }
}
