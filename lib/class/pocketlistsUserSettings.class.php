<?php

class pocketlistsUserSettings
{
    private $settings = array();

    const ICON_OVERDUE = 0;
    const ICON_OVERDUE_TODAY= 1;
    const ICON_OVERDUE_TODAY_AND_TOMORROW = 2;
    const ICON_NONE = 3;

    const DAILY_RECAP_FOR_TODAY = 0;
    const DAILY_RECAP_FOR_TODAY_AND_TOMORROW = 1;
    const DAILY_RECAP_FOR_NEXT_7_DAYS = 2;

    const EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_CREATED = 0;
    const EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE = 1;
    const EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST = 2;
    const EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM = 3;

    const EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_LIST_FAVORITE = 0;
    const EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST = 1;

    public function __construct($contact_id = false)
    {
        $cs = new waContactSettingsModel();
        $app_name = wa()->getApp();
        $this->settings = $cs->get($contact_id ? $contact_id : wa()->getUser()->getId(), $app_name);
    }

    /**
     * @return array
     */
    public function getAllSettings()
    {
        return $this->settings;
    }

    /**
     * @return bool
     */
    public function appIcon()
    {
        return !empty($this->settings['app_icon']) ? $this->settings['app_icon'] : 0;
    }

    /**
     * @return bool
     */
    public function emailDailyRecap()
    {
        return !empty($this->settings['daily_recap_on']) ? $this->settings['daily_recap'] : false;
    }

    /**
     * @return bool
     */
    public function emailWhenNewAssignToMe()
    {
        return !empty($this->settings['email_assign_me_on']) ? true : false;
    }
    /**
     * @return bool
     */
    public function emailWhenItemMarkedAsCompleted()
    {
        return !empty($this->settings['email_complete_item_on']) ? $this->settings['email_complete_item'] : false;
    }

    /**
     * @return bool
     */
    public function emailWhenAddsItem()
    {
        return !empty($this->settings['email_add_item_on']) ? $this->settings['email_add_item'] : false;
    }

    /**
     * @return bool
     */
    public function isCreateNewList()
    {
        return !empty($this->settings['email_create_list_on']) ? true : false;
    }

}