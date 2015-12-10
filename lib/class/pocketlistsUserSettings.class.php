<?php

class pocketlistsUserSettings
{
    private static $instance;
    private $settings = array();

    private function __construct()
    {
        $cs = new waContactSettingsModel();
        $app_name = 'pocketlists';//wa()->getApp();
        $settings = $cs->get(wa()->getUser()->getId(), $app_name);

        $this->settings = array(
            'app_icon' => isset($settings['app_icon']) ? $settings['app_icon'] : 0,
            'email_me' => json_decode(isset($settings['email_me']) ? $settings['email_me'] : "", true),
            'daily_recap' => json_decode(isset($settings['daily_recap']) ? $settings['daily_recap'] : "", true)
        );
    }

    /**
     *
     * @return pocketlistsUserSettings
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return array
     */
    public static function getAllSettings()
    {
        $instance = self::getInstance();
        return $instance->settings;
    }

    /**
     * @return bool
     */
    public static function isDailyRecapForToday()
    {
        $instance = self::getInstance();
        return (!empty($instance->settings['daily_recap']['next']) &&
            $instance->settings['daily_recap']['next']['enable'] == 1 &&
            $instance->settings['daily_recap']['next']['which'] == 1);
    }

    /**
     * @return bool
     */
    public static function isDailyRecapForTodayAndTomorrow()
    {
        $instance = self::getInstance();
        return (!empty($instance->settings['daily_recap']['next']) &&
            $instance->settings['daily_recap']['next']['enable'] == 1 &&
            $instance->settings['daily_recap']['next']['which'] == 2);
    }

    /**
     * @return bool
     */
    public static function isDailyRecapForNext7Days()
    {
        $instance = self::getInstance();
        return (!empty($instance->settings['daily_recap']['next']) &&
            $instance->settings['daily_recap']['next']['enable'] == 1 &&
            $instance->settings['daily_recap']['next']['which'] == 3);
    }

    /**
     * @return bool
     */
    public static function emailWhenNewAssignToMe()
    {
        $instance = self::getInstance();
        return (!empty($instance->settings['email_me']['assigns_me_item']) &&
            $instance->settings['email_me']['assigns_me_item']['enable'] == 1);
    }
}