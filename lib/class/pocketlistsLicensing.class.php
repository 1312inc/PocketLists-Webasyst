<?php

/**
 * Class pocketlistsLicensing
 */
class pocketlistsLicensing
{
    public static function isPremium()
    {
        $is_premium = wa()->getSetting('license_premium', '', pocketlistsHelper::APP_ID);
        if ($is_premium) {
            return true;
        }

        return false;
    }
}
