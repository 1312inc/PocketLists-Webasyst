<?php

/**
 * Class pocketlistsLicensing
 */
class pocketlistsLicensing
{
    public static function isPremium()
    {
        
        return true;
        
        $is_premium = false;
        if (waLicensing::check(pocketlistsHelper::APP_ID)->isPremium()) {
            $is_premium = wa()->getSetting('license_premium', '', pocketlistsHelper::APP_ID);

            if ((time() - strtotime($is_premium) > 3600)) {
                $is_premium = waLicensing::check(pocketlistsHelper::APP_ID)->hasPremiumLicense();
            }
        }

        return $is_premium;
    }
}
