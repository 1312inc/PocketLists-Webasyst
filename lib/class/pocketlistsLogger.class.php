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
            $msg = is_string($msg) ? $msg : print_r($msg, 1);
            self::log($msg, $file);
        }
    }

    /**
     * @param mixed  $msg
     * @param string $file
     */
    public static function log($msg, $file = 'pocketlists.log')
    {
        waLog::log(is_string($msg) ? $msg : print_r($msg, 1), 'pocketlists/'.$file);
        if (waSystemConfig::isDebug() && wa()->getEnv() === 'cli') {
            echo sprintf("%s %s\tpl2: %s\n", date('Y-m-d H:i:s'), microtime(true), $msg);
        }
    }

    /**
     * @param mixed  $msg
     * @param string $file
     */
    public static function error($msg, $file = 'error.log')
    {
        self::log($msg, $file);
    }
}
