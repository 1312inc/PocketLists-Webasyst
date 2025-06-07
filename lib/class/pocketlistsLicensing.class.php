<?php

/**
 * Class pocketlistsLicensing
 */
class pocketlistsLicensing
{
    /**
     * @return bool
     * @throws waDbException
     * @throws waException
     */
    public static function isPremium()
    {
        $is_premium = wa()->getSetting('license_premium', '', pocketlistsHelper::APP_ID);
        if ($is_premium) {
            return true;
        }

        if (waLicensing::check(pocketlistsHelper::APP_ID)->hasPremiumLicense()) {
            $app_settings = new waAppSettingsModel();
            $app_settings->set(pocketlistsHelper::APP_ID, 'license_premium', date('Y-m-d H:i:s'));
            return true;
        }

        return false;
    }
}
