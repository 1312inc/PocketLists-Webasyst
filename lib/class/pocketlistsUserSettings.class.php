<?php

class pocketlistsUserSettings
{
    private $settings;
    private $contact_id;
    private $csm;
    private $app_id = 'pocketlists';

    const ICON_OVERDUE = 1;
    const ICON_OVERDUE_TODAY= 2;
    const ICON_OVERDUE_TODAY_AND_TOMORROW = 3;
    const ICON_NONE = 0;

    const DAILY_RECAP_FOR_TODAY = 0;
    const DAILY_RECAP_FOR_TODAY_AND_TOMORROW = 1;
    const DAILY_RECAP_FOR_NEXT_7_DAYS = 2;

    const EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_CREATED = 0;
    const EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE = 1;
    const EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST = 2;
    const EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM = 3;

    const EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_FAVORITE_LIST = 0;
    const EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST = 1;

    const EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM = 0;
    const EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_FAVORITE_ITEM = 1;
    const EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_ANY_LIST_ITEM = 2;

    public function __construct($contact_id = false)
    {
        $this->csm = new waContactSettingsModel();
        $this->contact_id = $contact_id ? $contact_id : wa()->getUser()->getId();
        $this->settings = $this->csm->get($this->contact_id, $this->app_id);
    }

    public function saveDefaults()
    {
        foreach ($this->getDefaults() as $name => $value) {
            $this->set($name, $value);
        }
    }

    public function getDefaults()
    {
        return array(
            'app_icon' => self::ICON_OVERDUE_TODAY,
            'daily_recap_on' => 1,
            'daily_recap' => self::DAILY_RECAP_FOR_TODAY,
            'email_assign_me' => 1,
            'email_complete_item_on' => 1,
            'email_complete_item' => self::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_CREATED,
            'email_add_item_on' => 1,
            'email_add_item' => self::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_FAVORITE_LIST,
            'email_comment_item_on' => 1,
            'email_comment_item' => self::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM,
            'email_create_list_on' => 1,
            'stream_inbox_list' => 0
        );
    }

    /**
     * returns array of zero settings before save new
     * @return array
     */
    public function getZeroSettings()
    {
        return array(
            'daily_recap_on' => 0,
            'email_assign_me' => 0,
            'email_complete_item_on' => 0,
            'email_add_item_on' => 0,
            'email_comment_item_on' => 0,
            'email_create_list_on' => 0,
            'stream_inbox_list' => 0
        );
    }

    public function set($name, $value)
    {
        return $this->csm->set($this->contact_id, $this->app_id, $name, $value);
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
        return isset($this->settings['app_icon']) ? $this->settings['app_icon'] : false;
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
        return !empty($this->settings['email_assign_me']) ? true : false;
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
    public function emailWhenAddsComment()
    {
        return !empty($this->settings['email_comment_item_on']) ? $this->settings['email_comment_item'] : false;
    }

    /**
     * @return bool
     */
    public function isCreateNewList()
    {
        return !empty($this->settings['email_create_list_on']) ? true : false;
    }

    public function getLastPocketList()
    {
        return !empty($this->settings['last_pocket_list_id']) ? json_decode($this->settings['last_pocket_list_id'], true) : false;
    }

    public function getStreamInboxList()
    {
        return !empty($this->settings['stream_inbox_list']) ? $this->settings['stream_inbox_list'] : false;
    }
}
