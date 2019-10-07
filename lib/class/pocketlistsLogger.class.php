<?php

/**
 * Class pocketlistsLogger
 */
class pocketlistsLogger
{
    /**
     * @param mixed  $msg
     * @param string $file
     */
    public static function debug($msg, $file = 'debug.log')
    {
        if (waSystemConfig::isDebug()) {
            waLog::log(is_string($msg) ? $msg : print_r($msg, 1), 'pocketlists/'.$file);
        }
    }
}
