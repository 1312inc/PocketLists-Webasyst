<?php

class pocketlistsHelper
{
    public static function getUserSettings()
    {
        $cs = new waContactSettingsModel();
        $app_name = wa()->getApp();
        $settings = $cs->get(wa()->getUser()->getId(), $app_name);

        return array(
            'app_icon' => isset($settings['app_icon']) ? $settings['app_icon'] : 0,
            'email_me' => json_decode(isset($settings['email_me']) ? $settings['email_me'] : "", true),
            'daily_recap' => json_decode(isset($settings['daily_recap']) ? $settings['daily_recap'] : "", true)
        );
    }
}