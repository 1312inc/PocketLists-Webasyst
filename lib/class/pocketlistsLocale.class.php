<?php

/**
 * Class pocketlistsLocale
 */
class pocketlistsLocale extends waLocale
{
    /**
     * @var array
     */
    private static $localePaths = [];

    /**
     * @param string $locale
     * @param string $plugin
     */
    public static function forceLoad($locale, $plugin = '')
    {
        $domain = 'pocketlists';
        $localePath = 'locale';
        if ($plugin) {
            $localePath = 'plugins/'.$plugin.'/locale';
            $domain .= '_'.$plugin;
        }

        if (empty(self::$localePaths[$plugin])) {
            self::$localePaths[$plugin] = wa()->getAppPath($localePath, 'pocketlists');
        }
        $localePath = self::$localePaths[$plugin];

        parent::load($locale, $localePath, $domain);
//        parent::loadByDomain($plugin ? explode('_', $domain) : $domain, $locale);
    }
}
