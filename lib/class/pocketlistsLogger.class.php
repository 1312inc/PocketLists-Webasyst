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
            self::log(is_string($msg) ? $msg : print_r($msg, 1), $file);
        }
    }

    /**
     * @param mixed  $msg
     * @param string $file
     */
    public static function log($msg, $file = 'pocketlists.log')
    {
        waLog::log(is_string($msg) ? $msg : print_r($msg, 1), 'pocketlists/'.$file);
    }

    /**
     * @param mixed  $msg
     * @param string $file
     */
    public static function error($msg, $file = 'error.log')
    {
        waLog::log(is_string($msg) ? $msg : print_r($msg, 1), $file);
    }
}
